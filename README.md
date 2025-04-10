# Sistema de Análise de Feedback para Candidatos
## 📌 Visão Geral
Sistema desenvolvido em Laravel para análise e geração de feedbacks humanizados para candidatos em processos seletivos, utilizando técnicas de processamento de linguagem natural e machine learning.

## 🚀 Como Instalar
### Pré-requisitos
   - PHP 8.2+

   - Composer 2.5+

   - MySQL 8.0+ ou SQLite

   - Node.js 18+ (para assets frontend)

## Passo a Passo
### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/feedback-system.git
cd feedback-system
```

### 2. Instalar dependências

```bash
composer install
npm install
```

### 3. Configurar ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar banco de dados

```ini
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=feedback_system
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Configuração da Chave de API de IA
#### 5.1 Adicione no seu arquivo .env:

```ini
AI_API_KEY=sua_chave_da_api_aqui
AI_SERVICE_URL=https://api.servico-ia.com/v1/generate
```
#### 5.2 Configure no arquivo config/services.php:

```php
'ai' => [
    'api_key' => env('AI_API_KEY'),
    'endpoint' => env('AI_SERVICE_URL')
],
```

### 6. Configuração do Mailtrap
#### 6.1 Adicione no seu .env:

```ini
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_username_mailtrap
MAIL_PASSWORD=sua_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@feedbacksystem.com"
MAIL_FROM_NAME="Sistema de Feedback"
```

#### 6.2 Configure no arquivo config/mail.php:

```php
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'no-reply@feedbacksystem.com'),
    'name' => env('MAIL_FROM_NAME', 'Sistema de Feedback'),
],
```

### 7. Executar migrações

```bash
php artisan migrate --seed
```

### 8. Iniciar servidor

```bash

php artisan serve
npm run dev
```

## 🔧 Funcionalidades Principais
### 1. Cadastro de Feedback
    - Geração automática de feedback com IA

    - Análise de sentimento (positivo/negativo/neutro)

    - Sugestões de melhoria

### 2. Painel Administrativo
    - Visualização de todos os feedbacks

    - Filtros por candidato, processo e sentimento

    - Exportação para Excel

### 3. Envio para Candidatos
    - Envio automático por e-mail

    - Templates personalizáveis

## 🛠 Uso do Sistema
Para Recrutadores
### 1. Acessar o sistema

```
http://localhost:8000/login
```

### 2. Cadastrar novo feedback

    - Acessar perfil do candidato

    - Clicar em "Criar Feedback"

    - Preencher pontos fortes, melhorias e impressão geral

    - O sistema gerará automaticamente um feedback humanizado

### 3. Analisar sentimento

    - O sistema classifica automaticamente o tom do feedback

    - Fornece sugestões para melhorar a construção do texto

### 4. Enviar para candidato

    - Revisar o feedback gerado

    - Clicar em "Enviar ao Candidato"

## Para Administradores
### 1. Relatórios

    - Acessar /admin/feedbacks

    - Filtrar por período, processo ou sentimento

    - Exportar dados para análise

### 2. Gestão de Modelos

    - Acessar /admin/models

    - Treinar novo modelo com dados atualizados

    - Avaliar precisão da análise de sentimentos

# 🤖 Integração com IA
O sistema utiliza o pacote PHP-ML para:

```php
use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;

// Exemplo de análise
$analyzer = new CandidateFeedbackAnalyzer();
$feedback = "O candidato demonstrou excelentes habilidades técnicas";
$result = $analyzer->analyzeFeedback($feedback);
```

# 📊 Estrutura do Banco de Dados
Principais tabelas:

    - candidates (Candidatos)

    - processes (Processos seletivos)

    - feedbacks (Feedbacks gerados)

    - feedback_analysis (Metadados de análise)

# 🛡️ Segurança
    - Autenticação via Laravel Sanctum

    - CORS configurado

    - Validação de dados em todas as entradas

    - Logging de atividades sensíveis

# 📈 Melhorias Planejadas
    - Integração com ChatGPT para geração de feedback

    - Painel de métricas de satisfação

    - Módulo de acompanhamento pós-feedback

Desenvolvido por Luiz Santos © 2025 - Todos os direitos reservados