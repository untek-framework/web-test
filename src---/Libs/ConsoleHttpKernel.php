<?php

namespace Untek\Framework\WebTest\Libs;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Process\Process;
use Untek\Core\Contract\Encoder\Interfaces\EncoderInterface;
use Untek\Framework\Console\Domain\Helpers\CommandLineHelper;
use Untek\Framework\WebTest\Encoders\IsolateEncoder;

class ConsoleHttpKernel implements HttpKernelInterface
{
    protected EncoderInterface $encoder;

    protected string $endpointScript;

    public function __construct(IsolateEncoder $encoder, string $endpointScript)
    {
        $this->encoder = $encoder;
        $this->endpointScript = $endpointScript;
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $encodedRequest = $this->encoder->encode($request);
        $encodedResponse = $this->runConsoleCommand($encodedRequest);
        return $this->encoder->decode($encodedResponse);
    }

    protected function runConsoleCommand(string $encodedRequest): string
    {
        $command = [
            'php',
            $this->endpointScript,
            "--request" => $encodedRequest
        ];
        $commandString = CommandLineHelper::argsToString($command);
        $process = Process::fromShellCommandline($commandString);
        $process->run();
        $out = $process->getOutput() ?: $process->getErrorOutput();
        if ($process->getExitCode() > 0) {
            throw new \Exception($out);
        }
        return $out;
    }
}
