@component('mail::message')
# Olá, {{ $candidate->name }}!

Agradecemos sua participação no processo seletivo para **{{ $candidate->process->title }}**.

Aqui está o feedback sobre sua participação:

@component('mail::panel')
{!! nl2br(e($feedback->ai_generated_feedback)) !!}
@endcomponent

Agradecemos seu interesse em nossa empresa e desejamos sucesso em sua jornada profissional!

Atenciosamente,  
Equipe de Recrutamento
@endcomponent