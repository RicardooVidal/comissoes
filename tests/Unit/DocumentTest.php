<?php

namespace Tests\Unit;

use App\Helpers\Document;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cpf_invalido_retorna_false(): void
    {
        $this->assertFalse(Document::validateCPF('44075834523'));
    }
}
