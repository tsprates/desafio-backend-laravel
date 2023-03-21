# Projeto


### Como usar

Use o seguinte comando para fazer a instalação da aplicação:

```sh
docker-compose up --build
```

> Ao iniciar o projeto pela primeira vez é necessário instalar as dependências através do **Composer**. 

Para realizar a instalação das dependências do _Laravel_, utilize o seguinte comando:

```sh
docker-compose exec -it php composer install
```

E o seguinte para migração das tabelas utilizadas pelo _PostgreSQL_:

```sh
docker-compose exec -it php php artisan migrate
```

> Os arquivos de configurações do **Supervisor** e **NGINX** podem ser encontradas dentro da pasta `conf`, na raiz do projeto. Variáveis de ambiente do Laravel podem ser alteradas por meio do arquivo `.env.laravel` na raiz do projeto.


### API de Pacientes

Os dados do paciente podem ser _criados_, _alterados_ e _deletados_ por meio da seguinte URL:

```
http://localhost/api/pacientes
```

Exemplo de importação de pacientes, onde `exemplo.json` é um arquivo de exemplo:

```sh
curl -X POST -F 'arquivo=@exemplo.csv' http://localhost/api/import
```


### Consulta de CPF

Para realizar uma consultada de determinado CPF, utilize a seguinte URL:

```sh
curl http://localhost/api/cpf?cpf=123.456.789-00
```

> A formatacão com pontuação do CPF é _opcional_.


### ViaCep

Para consultar determinado CEP, utilize a seguinte URL com o CEP procurado:

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

Para realizar buscas no _Elasticsearch_ é necessário utilizar a seguinte URL. O `{termo}` na URL deve ser substituído pelo termo a ser buscado.

```sh
curl http://localhost/api/search/{texto}
```


### Testes

Para realização dos testes utilize:

```sh
docker-compose exec -it php php artisan test
```
