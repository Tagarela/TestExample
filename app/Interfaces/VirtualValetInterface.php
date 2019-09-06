<?php

namespace App\Interfaces;

interface VirtualValetInterface
{
    public function getAmount();
    public function addAmount(int $amount);
}