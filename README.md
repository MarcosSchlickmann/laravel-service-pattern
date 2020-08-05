## Configuração inicial

### 1. [Habilite para rodas os comandos do docker sem sudo](https://github.com/sindresorhus/guides/blob/master/docker-without-sudo.md)

### 2. Clone o repositório

### 3. Prepare o ambiente do docker-compose

Acesse o repositório e crie o arquivo de configuração do ambiente:
     
     cd e-saude-backend
     cp .env.example .env
     
Execute os comando a seguir para que defina seu ID de usuário e de grupo do sistema:

     sed -i -e "s/USER_ID=1000/USER_ID=$UID/" .env
     sed -i -e "s/GROUP_ID=1000/GROUP_ID=$UID/" .env
     
### 4. Inicie o ambiente com o docker-compose

Para iniciar exibindo o relatório execute `docker-compose up --build`. 
Para não exibir relatório, execute `docker-compose up -d --build`.

Agora, para executar comandos no sistema **sempre** utilize o formato:
`docker-compose exec app <comando>`

### 5. Configure o sistema Laravel

#### 5.1. Configurações do ambiente

Abra o repositório no sublime, acesse a pasta do sistema e crie o arquivo de configurações. Após, abra-o para configurar.

    subl .
    cd html
    cp .env.example .env
    subl .env

Nesse arquivo você pode alterar o banco de dados a ser usado, configurar um servidor de e-mails, etc.

Para melhorar o desempenho, crie um banco de dados local. Após, configure as informações de acesso no arquivo`html/.env`:

	DB_CONNECTION=mysql
	DB_HOST=192.168.0.4
	DB_PORT=3306
	DB_DATABASE=renault-copa
	DB_USERNAME=username
	DB_PASSWORD=password

Obs: Em 'DB_HOST' pode ser necessário usar o IP local da sua máquina para o docker encontrá-la. Conforme feito acima.

#### 5.2. Instalar dependências
Execute `docker-compose exec app composer install` para instalar as dependências e bibliotecas do PHP para o Laravel.

#### 5.3. Gere uma chave para a aplicação
`docker-compose exec app php artisan key:generate`

#### 5.4. Rode as migrações do banco de dados
`docker-compose exec app php artisan migrate`

### Atenção
**Todos os comandos devem ser executados dentro do docker, principalmente os do composer e do artisan, com o seguinte comando:**

	docker-compose exec app <seu comando aqui>

Isso é necessário, pois, o composer e o laravel estão configurados dentro do container Docker.
Se seus comandos forem rodados fora do docker serão utilizadas as versões e configurações do seu computador,
podendo trazer problemas.