<?php

declare(strict_types=1);

namespace App\Service;

abstract class AbstractEmployeeFileOperations
{
    protected const FILE_HEADERS = [
        'ID',
        'First Name',
        'Last Name',
        'Email',
        'Company',
        'Project',
        'Salary',
        'Bonus',
        'Insurance Amount',
        'Total Cost',
        'Employment Start',
        'Employment End'
    ];
}