# 🚀 StockMaster - Web Full Stack

![Status](https://img.shields.io/badge/status-finalizado-brightgreen)

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/Doctrine%20ORM-3.6-blue?style=for-the-badge&logo=doctrine">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap">
  <img src="https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge&logo=javascript">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql">
</p>

<p align="center">Sistema completo de gestão de estoque com interface moderna, profissional e pronta para produção.</p>
<p align="center"><strong>Desenvolvido exclusivamente para fins acadêmicos</strong> por <a href="https://github.com/Carlos-oestreich">Carlos Eduardo Oestreich</a> e <a href="https://github.com/larissalaumann">Larissa Maria Laumann</a>.</p>

---

## 📌 Sobre o Projeto

O **StockMaster** é um sistema completo de gestão de estoque desenvolvido em **PHP 8.2** com **Doctrine ORM** e interface moderna com **Bootstrap 5.3**.

- Arquitetura em camadas seguindo padrão **MVC**.
- Código modular, limpo e pronto para escala.
- Interface 100% responsiva e intuitiva.
- Desenvolvido para fins acadêmicos, mas pronto para produção.

> **Nota:** Este repositório contém o sistema full stack (frontend + backend integrados).

---

## 🔗 Versões em Outras Linguagens

Também desenvolvemos este mesmo sistema em outras linguagens:

**Versão em Java:**  
[StockMaster Backend (Java + Spring Boot)](https://github.com/Carlos-oestreich/Projeto_StockMaster)

---

## ✨ Funcionalidades Principais

- Autenticação com criptografia bcrypt
- Cadastro inicial de empresa e usuário
- Gestão de categorias, produtos e fornecedores
- Controle de movimentações de estoque
- Dashboard com métricas em tempo real
- Alertas de estoque baixo
- Relatórios completos em PDF
- Tema claro/escuro com persistência
- Interface totalmente responsiva

---

## 🧱 Arquitetura

O projeto segue arquitetura em camadas, separando responsabilidades para facilitar manutenção e evolução:

```
src/
├── controller/          # Controllers (lógica de requisição)
├── model/               # Entidades do Doctrine ORM
├── dao/                 # Data Access Object
├── view/                # Views (templates PHP + HTML/Bootstrap)
└── utils/               # Utilitários (Conexão com BD)

public/
├── index.php            # Router principal
└── assets/              # CSS, JavaScript, imagens
```

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.2**
- **Doctrine ORM 3.6**
  - Mapeamento objeto-relacional
  - Proteção contra SQL Injection
  - Transações ACID
- **Bootstrap 5.3**
  - Framework CSS responsivo
  - Componentes prontos
  - Grid system
- **JavaScript ES6+**
  - Validações em tempo real
  - Máscaras de entrada
  - Geração de PDF (jsPDF)
- **MySQL 5.7+**
- **Composer** (gerenciamento de dependências)

---

## 📡 Rotas Principais

### 🔐 Autenticação

- `GET  /login` — Exibe formulário de login
- `POST /login/entrar` — Processa login
- `POST /login/sair` — Realiza logout

### 📦 Produtos

- `GET    /produtos` — Lista produtos
- `POST   /produtos/salvar` — Cria novo produto
- `POST   /produtos/{id}/salvar` — Atualiza produto
- `POST   /produtos/{id}/deletar` — Deleta produto

### 🏷️ Categorias (Admin)

- `GET    /categorias`
- `POST   /categorias/salvar`
- `POST   /categorias/{id}/deletar`

### 🚚 Fornecedores (Admin)

- `GET    /fornecedores`
- `POST   /fornecedores/salvar`
- `POST   /fornecedores/{id}/deletar`

### 📌 Movimentações

- `GET    /movimentacoes` — Histórico
- `POST   /movimentacoes/salvar` — Registra movimento

### 📊 Dashboard

- `GET /dashboard` — Métricas e alertas

---

## 🛡️ Segurança

- **Criptografia bcrypt** para senhas
- **Prepared Statements** via Doctrine ORM
- **Sanitização de entrada** com filter_input()
- **Escapar saída** com htmlspecialchars()
- **Proteção de rotas** com verificação de sessão
- **CSRF Token** em formulários
- **Nunca suba credenciais sensíveis no repositório!**

---

## ⚙️ Configuração

Configure as variáveis de acesso ao banco no arquivo `.env`:

```env
DB_DRIVER=pdo_mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=stockmaster
DB_USER=root
DB_PASSWORD=sua_senha
```

Use o arquivo `.env.example` como referência.

---

## ▶️ Como Executar

1. **Clonar o projeto**
   ```bash
   git clone https://github.com/Carlos-oestreich/StockMasterWeb.git
   cd StockMasterWeb
   ```

2. **Instalar dependências**
   ```bash
   composer install
   ```

3. **Configurar variáveis de ambiente**
   ```bash
   cp .env.example .env
   # Edite o arquivo .env com suas credenciais
   ```

4. **Executar**

   **Usando XAMPP:**
   - Coloque a pasta em `C:/xampp/htdocs/`
   - Acesse: `http://localhost/StockMasterWeb`

   **Usando PHP built-in:**
   ```bash
   php -S localhost:8000 -t public
   ```
   - Acesse: `http://localhost:8000`

---

### 💻 Ambiente de Desenvolvimento

Este projeto foi desenvolvido e testado utilizando:

- **XAMPP** (servidor web)
- **Visual Studio Code** + PHP Intelephense
- **MySQL Workbench** (gerenciamento de BD)
- **HeidiSQL** (gerenciador de BD)

Você pode executar em qualquer ambiente que suporte PHP 8.2+ e MySQL.

---

## 🧪 Testes

Testes unitários inclusos com **PHPUnit**:

```bash
# Executar todos os testes
./vendor/bin/phpunit test/

# Executar teste específico
./vendor/bin/phpunit test/UsuarioDAOTest.php
```

Teste as rotas manualmente via navegador.

---

## 📱 Responsividade

Sistema 100% responsivo com suporte para:
- 📱 **Mobile** (320px+)
- 📱 **Tablet** (768px+)
- 💻 **Desktop** (1024px+)

---

## 🎨 Tema Claro/Escuro

Sistema com tema claro e escuro integrado:
- 🌙 Tema escuro (padrão)
- ☀️ Tema claro

Preferência salva em `localStorage`.

---

## 📄 Geração de Relatórios

Relatórios em PDF com:
- Dados da empresa
- Tabelas formatadas
- Design profissional

---

## 🔒 Padrão MVC

Implementação rigorosa do padrão MVC:
- **Model:** Entidades Doctrine
- **View:** Templates PHP + Bootstrap
- **Controller:** Lógica de negócio

---

## 👨‍💻 Autores

- [Carlos Eduardo Oestreich](https://github.com/Carlos-oestreich)
- [Larissa Maria Laumann](https://github.com/larissalaumann)

---

## 📌 Observação Final

Este projeto foi desenvolvido para fins acadêmicos e atende a todos os requisitos técnicos solicitados, seguindo boas práticas e padrões profissionais.

<div align="center">

💡 Dúvidas, feedbacks e colaborações são bem-vindos!

⭐ Se foi útil, deixe uma estrela!

</div>

---

---

# 🇬🇧 English Version

---

# 🚀 StockMaster - Web Full Stack

![Status](https://img.shields.io/badge/status-completed-brightgreen)

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/Doctrine%20ORM-3.6-blue?style=for-the-badge&logo=doctrine">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap">
  <img src="https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge&logo=javascript">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql">
</p>

<p align="center">Complete inventory management system with modern, professional interface ready for production.</p>
<p align="center"><strong>Developed exclusively for academic purposes</strong> by <a href="https://github.com/Carlos-oestreich">Carlos Eduardo Oestreich</a> and <a href="https://github.com/larissalaumann">Larissa Maria Laumann</a>.</p>

---

## 📌 About the Project

**StockMaster** is a complete inventory management system developed in **PHP 8.2** with **Doctrine ORM** and modern **Bootstrap 5.3** interface.

- Layered architecture following **MVC pattern**.
- Modular, clean, and scale-ready codebase.
- 100% responsive and intuitive interface.
- Developed for academic purposes, but ready for production.

> **Note:** This repository contains the full stack system (frontend + backend integrated).

---

## 🔗 Versions in Other Languages

We have also developed this same system in other languages:

**Java Version:**  
[StockMaster Backend (Java + Spring Boot)](https://github.com/Carlos-oestreich/Projeto_StockMaster)

---

## ✨ Main Features

- Authentication with bcrypt encryption
- Initial company and user registration
- Categories, products and suppliers management
- Stock movement control
- Dashboard with real-time metrics
- Low stock alerts
- Complete PDF reports
- Light/dark theme with persistence
- Fully responsive interface

---

## 🧱 Architecture

The project follows a layered architecture, separating responsibilities for easy maintenance and evolution:

```
src/
├── controller/          # Controllers (request logic)
├── model/               # Doctrine ORM entities
├── dao/                 # Data Access Object
├── view/                # Views (PHP templates + HTML/Bootstrap)
└── utils/               # Utilities (Database Connection)

public/
├── index.php            # Main router
└── assets/              # CSS, JavaScript, images
```

---

## 🚀 Technologies Used

- **PHP 8.2**
- **Doctrine ORM 3.6**
  - Object-relational mapping
  - SQL Injection protection
  - ACID transactions
- **Bootstrap 5.3**
  - Responsive CSS framework
  - Ready-to-use components
  - Grid system
- **JavaScript ES6+**
  - Real-time validations
  - Input masks
  - PDF generation (jsPDF)
- **MySQL 5.7+**
- **Composer** (dependency management)

---

## 📡 Main Routes

### 🔐 Authentication

- `GET  /login` — Display login form
- `POST /login/entrar` — Process login
- `POST /login/sair` — Logout

### 📦 Products

- `GET    /produtos` — List products
- `POST   /produtos/salvar` — Create product
- `POST   /produtos/{id}/salvar` — Update product
- `POST   /produtos/{id}/deletar` — Delete product

### 🏷️ Categories (Admin)

- `GET    /categorias`
- `POST   /categorias/salvar`
- `POST   /categorias/{id}/deletar`

### 🚚 Suppliers (Admin)

- `GET    /fornecedores`
- `POST   /fornecedores/salvar`
- `POST   /fornecedores/{id}/deletar`

### 📌 Movements

- `GET    /movimentacoes` — History
- `POST   /movimentacoes/salvar` — Record movement

### 📊 Dashboard

- `GET /dashboard` — Metrics and alerts

---

## 🛡️ Security

- **bcrypt encryption** for passwords
- **Prepared Statements** via Doctrine ORM
- **Input sanitization** with filter_input()
- **Output escaping** with htmlspecialchars()
- **Route protection** with session verification
- **CSRF Token** in forms
- **Never upload sensitive credentials to the repository!**

---

## ⚙️ Configuration

Configure database variables in `.env` file:

```env
DB_DRIVER=pdo_mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=stockmaster
DB_USER=root
DB_PASSWORD=your_password
```

Use `.env.example` as reference.

---

## ▶️ How to Run

1. **Clone the project**
   ```bash
   git clone https://github.com/Carlos-oestreich/StockMasterWeb.git
   cd StockMasterWeb
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment variables**
   ```bash
   cp .env.example .env
   # Edit the .env file with your credentials
   ```

4. **Run**

   **Using XAMPP:**
   - Place the folder in `C:/xampp/htdocs/`
   - Access: `http://localhost/StockMasterWeb`

   **Using PHP built-in:**
   ```bash
   php -S localhost:8000 -t public
   ```
   - Access: `http://localhost:8000`

---

### 💻 Development Environment

This project was developed and tested using:

- **XAMPP** (web server)
- **Visual Studio Code** + PHP Intelephense
- **MySQL Workbench** (database management)
- **HeidiSQL** (database manager)

You can run it on any environment that supports PHP 8.2+ and MySQL.

---

## 🧪 Testing

Unit tests included with **PHPUnit**:

```bash
# Run all tests
./vendor/bin/phpunit test/

# Run specific test
./vendor/bin/phpunit test/UsuarioDAOTest.php
```

Test the routes manually via browser.

---

## 📱 Responsiveness

System 100% responsive with support for:
- 📱 **Mobile** (320px+)
- 📱 **Tablet** (768px+)
- 💻 **Desktop** (1024px+)

---

## 🎨 Light/Dark Theme

System with integrated light and dark theme:
- 🌙 Dark theme (default)
- ☀️ Light theme

Preference saved in `localStorage`.

---

## 📄 Report Generation

PDF reports with:
- Company data
- Formatted tables
- Professional design

---

## 🔒 MVC Pattern

Strict implementation of MVC pattern:
- **Model:** Doctrine entities
- **View:** PHP templates + Bootstrap
- **Controller:** Business logic

---

## 👨‍💻 Authors

- [Carlos Eduardo Oestreich](https://github.com/Carlos-oestreich)
- [Larissa Maria Laumann](https://github.com/larissalaumann)

---

## 📌 Final Note

This project was developed for academic purposes and meets all requested technical requirements, following best practices and professional standards.

<div align="center">

💡 Questions, feedback, and collaborations are welcome!

⭐ If it was useful, leave a star!

</div>

