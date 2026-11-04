<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_inicio_responde_correctamente(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Sistema de préstamos de herramientas');
    }
}
