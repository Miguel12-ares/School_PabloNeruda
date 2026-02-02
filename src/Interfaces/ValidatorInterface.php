<?php

/**
 * Interfaz para validadores
 * Asegura que todos los validadores implementen el método validate
 */
interface ValidatorInterface {
    public function validate(array $data): array;
}

