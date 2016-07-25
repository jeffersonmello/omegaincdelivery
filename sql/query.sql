create database delivery;

use delivery;

create table cad_categorias (
	guid int not null auto_increment,
	descricao varchar(500) not null,
	iconecategoria varchar(500) default '<i class="fa fa-archive color-icon" aria-hidden="true"></i> ',
	primary key (guid)
);

create table cad_produtos (
	guid int not null auto_increment,
	guid_categoria int not null,
	imgproduto varchar(700),
	descricao varchar(500) not null,
	subdescricao	varchar(500),
	preco float,
	primary key (guid),
	foreign key (guid_categoria) references cad_categorias (guid)
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
	entregar int not null,
	status int not null,
	formaPagamento int not null,
	observacao text,
	notificado int not null default 0,
	visualizado int not null default 0,
	primary key (guid)
);

create table lanc_listprodpedido (
	guid int not null auto_increment,
	guid_produto int not null,
	guid_pedido int not null,
	primary key (guid),
	foreign key (guid_produto) references cad_produtos (guid),
	foreign key (guid_pedido) references lanc_pedidos (guid)
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
	nivel int not null default 0,
	primary key(guid)
);

create table adm_empresa (
	guid int not null auto_increment,
	nome varchar(500) not null,
	cnpj varchar(15),
	inscricaoestd varchar(15),
	telefone varchar(20),
	email varchar(50),
	endereco varchar(500),
	padrao int,
	aberto int,
	primary key (guid)
);
