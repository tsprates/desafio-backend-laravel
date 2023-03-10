# Projeto

### Como usar

```sh
docker-compose up --build
```

> **Observação**: Ao iniciar o projeto pela primeira vez é necessário instalar as dependências do `Laravel`. O arquivo de configuração do se encontra na raiz do projecto `.env.laravel`.

Para realizar a instalação de dependencias do _Laravel_ utilize o seguinte comando:

```sh
docker-compose exec -it php artisan composer install
```

E o seguinte comando para relizar a migração das tabelas do _PostgreSQL_:

```sh
docker-compose exec -it php artisan migrate
```

> **Observação**: Os arquivos de configurações dos serviços _NGINX_ e _Supervisor_ podem encontrados na pasta `conf` na raiz do projeto.

### Pacientes

Os dados do paciente podem ser **criados**, **alterados** e **deletados** utilizando a seguinte API:

```
http://localhost/api/pacientes
```

Para realizar a importação de pacientes utilize o seguinte endpoint:


```sh
curl -X POST -F 'arquivo=@exemplo.json' http://localhost/api/cpf?cpf=12345678900
```

> **Observação**: O arquivo `exemplo.json` é um arquivo de exemplo de pacientes.

### Consulta de CPF

Para realizar consultads de determinado CPF, utilize o seguinte endpoint:

```sh
curl http://localhost/api/cpf?cpf=12345678900
```

### ViaCep

Para consultar determinado CEP, utilize o seguinte endpoint.

```sh
curl http://localhost/api/cep?cep=01001000
```

Exemplo:

```json
{
  "cep": "01001-000",
  "logradouro": "Praça da Sé",
  "complemento": "lado ímpar",
  "bairro": "Sé",
  "localidade": "São Paulo",
  "uf": "SP",
  "ibge": "3550308",
  "gia": "1004",
  "ddd": "11",
  "siafi": "7107"
}
```

### Elasticsearch

Para realizar buscas no _Elasticsearch_, utilize o seguinte endpoint.

```sh
curl http://localhost/api/search/{texto}
```

### Testes

Para executar testes da aplicacão:

```sh
docker-compose exec -it php php artisan test
```
