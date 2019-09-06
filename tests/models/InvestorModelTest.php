<?php

declare(strict_types=1);

class InvestorModelTest extends TestCase
{
    public function testCreateModel()
    {
        $investorModel = new \App\Models\Investor();
        $this->assertTrue($investorModel instanceof \App\Models\Investor);
    }
}