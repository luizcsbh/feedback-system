<?php

namespace App\Enums;

enum CandidateStatus: string
{
    case pending = 'pendente';
    case approved = 'aprovado';
    case rejected = 'rejeitado';
}
