# Projeto

### Como usar

Use o seguinte comando para fazer a instalação da aplicação:

```sh
docker-compose up --build
```

> **Observação**: Ao iniciar o projeto pela primeira vez é necessário instalar as dependências do _Laravel_ através do **composer**. As variáveis de ambiente do Laravel podem ser alteradas através do arquivo `.env.laravel` que se encontram na raiz do projeto.


Caso precise realizar a instalação das dependencias do _Laravel_, use o seguinte comando:

```sh
docker-compose exec -it php artisan composer install
```

E realize a migração das tabelas utilizadas pelo _PostgreSQL_:

```sh
docker-compose exec -it php artisan migrate
```

> **Observação**: Arquivos de configurações dos serviços **Supervisor** e **NGINX** podem ser encontradas na raiz do projeto, dentro da pasta `conf`.

### API de Pacientes

Os dados do paciente podem ser **criados**, **alterados** e **deletados** utilizando a seguinte API:

```
http://localhost/api/pacientes
```

E o seguinte comando para realizar a importação de pacientes:

```sh
curl -X POST -F 'arquivo=@exemplo.json' http://localhost/api/cpf?cpf=12345678900
```

> **Observação**: O arquivo `exemplo.json` é um arquivo de exemplo para importação de pacientes.

### Consulta de CPF

Para realizar consultads de determinado CPF, utilize a seguinte URL:

```sh
curl http://localhost/api/cpf?cpf=123.456.789-00
```

> **Observação**: A formatacão é _opcional_.

### ViaCep

Para consultar determinado CEP, utilize a seguinte URL.

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

Para realizar buscas no _Elasticsearch_ é necessário utilizar a seguinte URL. O `{termo}` deve ser alterado para o item buscado.

```sh
curl http://localhost/api/search/{texto}
```

### Testes

Para executar testes da aplicacão:

```sh
docker-compose exec -it php php artisan test
```
