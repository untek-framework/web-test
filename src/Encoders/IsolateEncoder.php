<?php

namespace Untek\Framework\WebTest\Encoders;

use Untek\Component\Encoder\Encoders\BaseChainEncoder;
use Untek\Component\Encoder\Encoders\PhpSerializeEncoder;
use Untek\Component\Encoder\Encoders\SafeBase64Encoder;
use Untek\Core\Collection\Libs\Collection;

/**
 * Кодировщик HTTP-запросов/ответов.
 *
 * Создает из объекта текстовое представление и наоборот.
 *
 * Используется для обмена объектами через командную строку.
 *
 * Десериализвация объекта выполняется в 2 шага:
 *
 * - Декодирование из формата `Base64`.
 * - Десериализация объекта (функция `unserialize`).
 *
 *
 * Сериализация объекта выполняется в 2 шага:
 *
 * - Сериализация объекта (функция `serialize`).
 * - Кодирование в формат `Base64`.
 */
class IsolateEncoder extends BaseChainEncoder
{
    public function __construct()
    {
        $this->encoderCollection = new Collection();
        $this->encoderCollection->add(new PhpSerializeEncoder());
        $this->encoderCollection->add(new SafeBase64Encoder());
    }
}
