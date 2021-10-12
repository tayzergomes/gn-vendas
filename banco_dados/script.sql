
CREATE DATABASE IF NOT EXISTS gn_vendas;

CREATE TABLE gn_vendas.produtos (
	id BIGINT UNSIGNED auto_increment NOT NULL PRIMARY KEY,
	nome varchar(100) NOT NULL,
	valor DOUBLE NOT NULL
);

CREATE TABLE gn_vendas.compras (
  id BIGINT UNSIGNED auto_increment NOT NULL PRIMARY KEY,
  nome varchar(100) NOT NULL,
  telefone varchar(100) NOT NULL,
  cpf varchar(15) NOT NULL,
  produto_id BIGINT UNSIGNED NOT NULL,
  FOREIGN KEY(produto_id) REFERENCES produtos(id),
  id_boleto varchar(100),
  link_boleto varchar(1024)
);

CREATE INDEX id_boleto_compras ON gn_vendas.compras (id_boleto);
CREATE INDEX cpf_compras ON gn_vendas.compras (cpf);
CREATE INDEX produto_id_compras ON gn_vendas.compras (produto_id);
