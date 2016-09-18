create database delivery;

use delivery;

create table cad_categorias (
	guid int not null auto_increment,
	descricao varchar(500) not null,
	twosaborescat int not null default 0;
	iconecategoria varchar(500) default '<i class="fa fa-archive color-icon" aria-hidden="true"></i> ',
	primary key (guid)
);

create table cad_produtos (
	guid int not null auto_increment,
	guid_categoria int not null,
	imgproduto varchar(700),
	descricao varchar(500) not null,
	subdescricao varchar(500),
	indisponivel int not null default 0,
	twosabores int not null default 0,
	preco float,
	primary key (guid),
	foreign key (guid_categoria) references cad_categorias (guid)
);

create table temp_prods (
	guid int not null auto_increment,
	descricao varchar(900),
	preco float,
	imagem varchar(700) default 'https://cdn1.iconfinder.com/data/icons/streamline-time/60/cell-18-2-240.png',
	primary key (guid)
);


/**
	*	STATUS
	* 0 = Processando Incompleto
	* 1 = Processando
	* 2 = Em Produção
	* 3 = Pronto
	* 4 = Aguardando para busca
	* 5 = Saiu para Entrega
	* 6 = Entegue
	* 7 = Cliente não estava
	* 8 = Cancelado
	* 9 = Devolvido
**/
create table lanc_pedidos (
	guid int not null auto_increment,
	guidresposta int, -- New --
	eguid int,
	nome varchar(500),
	email varchar(500),
	bairro varchar(500),
	token varchar(500),
	cpf varchar(15),
	total float,
	telefone varchar(500),
	endereco varchar(750),
	numero varchar(100),
	data date,
	dataalteracao datetime, -- New --
	entregar int not null,
	status int not null,
	formaPagamento int not null,
	troco varchar(250), -- New --
	observacao text,
	notificado int not null default 0,
	visualizado int not null default 0,
	primary key (guid)
	-- foreign key (guidresposta) references lanc_respostas (guid) --
);

create table lanc_listprodpedido (
	guid int not null auto_increment,
	guid_produto int not null,
	guid_pedido int not null,
	primary key (guid)
);

create table lanc_respostas (
	guid int not null auto_increment,
	usertype int not null,
	mensagem text,
	primary key (guid)
);

create table atd_bairros (
	guid int not null auto_increment,
	descricao varchar(500) not null,
	taxaEntrega float,
	primary key (guid)
);

create table adm_usuarios (
	guid int not null auto_increment,
	usuario varchar(250) not null,
	senha varchar(250) not null,
	nome varchar(500) not null,
	imagem varchar(750),
	nivel int not null default 0,
	primary key(guid)
);

create table adm_empresa (
	guid int not null auto_increment,
	nome varchar(500) not null,
	cnpj varchar(18),
	inscricaoestd varchar(20),
	telefone varchar(20),
	email varchar(50),
	endereco varchar(500),
	padrao int,
	aberto int,
	primary key (guid)
);

-- -----------------------------------------------------
-- Table `log_auditoria`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `log_auditoria` ;

CREATE TABLE IF NOT EXISTS `log_auditoria` (
	guid INT NOT NULL AUTO_INCREMENT,
	acao VARCHAR(750) NOT NULL,
	guidusuario INT NOT NULL,
	nomeusuario VARCHAR(500) NOT NULL,
	datahora DATETIME,
	PRIMARY KEY (guid)
);
