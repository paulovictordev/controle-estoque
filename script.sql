DROP DATABASE IF EXISTS `db_controle_estoque`;

CREATE SCHEMA `db_controle_estoque` DEFAULT CHARACTER SET utf8 ;

USE `db_controle_estoque`;

CREATE TABLE `db_controle_estoque`.`categoria` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT, 
    `categoria` VARCHAR(255) NULL
);

CREATE TABLE `db_controle_estoque`.`cidade` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
    `cidade` VARCHAR(255) NOT NULL , 
    `uf` CHAR(2) NOT NULL
);

CREATE TABLE `db_controle_estoque`.`fornecedor` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
    `cidade_id` INT NOT NULL , 
    `fornecedor` VARCHAR(255) NOT NULL , 
    `endereco` VARCHAR(255) NOT NULL , 
    `numero` VARCHAR(6) NULL , 
    `bairro` VARCHAR(255) NULL , 
    `cep` CHAR(9) NOT NULL , 
    `contato` VARCHAR(255) NULL , 
    `cnpj` CHAR(18) NOT NULL , 
    `inscricao_estadual` VARCHAR(255) NOT NULL , 
    `telefone` CHAR(14) NOT NULL ,
    FOREIGN KEY (`cidade_id`) REFERENCES `cidade` (`id`)
);

CREATE TABLE `db_controle_estoque`.`produto` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
    `categoria_id` INT NOT NULL , 
	`fornecedor_id` INT NOT NULL , 
    `descricao` VARCHAR(255) NOT NULL , 
    `peso` DOUBLE , 
    `controlado` BOOL , 
    `qtd_minima` VARCHAR(255) NOT NULL ,
    FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ,
    FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`)
);

CREATE TABLE `db_controle_estoque`.`transportadora` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
    `cidade_id` INT NOT NULL , 
    `transportadora` VARCHAR(255) NOT NULL , 
    `endereco` VARCHAR(255) NOT NULL , 
    `numero` VARCHAR(6) NULL , 
    `bairro` VARCHAR(255) NULL , 
    `cep` CHAR(9) NOT NULL , 
    `cnpj` CHAR(18) NOT NULL , 
    `inscricao_estadual` VARCHAR(255) NOT NULL , 
    `contato` VARCHAR(255) NULL , 
    `telefone` CHAR(14) NOT NULL ,
    FOREIGN KEY (`cidade_id`) REFERENCES `cidade` (`id`)
);

CREATE TABLE `db_controle_estoque`.`entrada` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT, 
    `transportadora_id` INT NOT NULL , 
    `data_pedido` DATE NOT NULL ,
    `data_entrada` DATE NOT NULL ,
    `total` DOUBLE NOT NULL ,
    `frete` DOUBLE ,
    `numero_nf` INTEGER NOT NULL ,
    `imposto` DOUBLE ,
    FOREIGN KEY (`transportadora_id`) REFERENCES `transportadora` (`id`)
);

CREATE TABLE `db_controle_estoque`.`item_entrada` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT, 
    `produto_id` INT NOT NULL ,
    `entrada_id` INT NOT NULL , 
    `lote` VARCHAR(255) ,
    `qtde` INT NOT NULL ,
    `valor` DOUBLE ,
    FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`),
    FOREIGN KEY (`entrada_id`) REFERENCES `entrada` (`id`)
);

CREATE TABLE `db_controle_estoque`.`loja` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
    `cidade_id` INT NOT NULL , 
    `nome` VARCHAR(255) NOT NULL , 
    `endereco` VARCHAR(255) NOT NULL , 
    `numero` VARCHAR(6) NULL , 
    `bairro` VARCHAR(255) NULL , 
    `telefone` CHAR(14) NOT NULL ,
    `inscricao_estadual` VARCHAR(255) NOT NULL , 
    `cnpj` CHAR(18) NOT NULL , 
    FOREIGN KEY (`cidade_id`) REFERENCES `cidade` (`id`)
);

CREATE TABLE `db_controle_estoque`.`saida` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT, 
    `loja_id` INT NOT NULL , 
    `transportadora_id` INT NOT NULL , 
    `total` DOUBLE NOT NULL ,
    `frete` DOUBLE NOT NULL ,
    `imposto` DOUBLE ,
    FOREIGN KEY (`loja_id`) REFERENCES `loja` (`id`),
    FOREIGN KEY (`transportadora_id`) REFERENCES `transportadora` (`id`)
);

CREATE TABLE `db_controle_estoque`.`item_saida` ( 
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT, 
    `saida_id` INT NOT NULL ,
    `produto_id` INT NOT NULL , 
    `lote` VARCHAR(255) ,
    `qtde` INT NOT NULL ,
    `valor` DOUBLE ,
    FOREIGN KEY (`saida_id`) REFERENCES `saida` (`id`),
    FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`)
);

CREATE TABLE `db_controle_estoque`.`usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);


-- INSERINDO DADOS

INSERT INTO `categoria` VALUES 
(1, 'Guitarra'),
(2, 'Baixo'),
(3, 'Violão'),
(4, 'Bateria'),
(5, 'Microfone'),
(6, 'Amplificador'),
(7, 'Cabos');

INSERT INTO `cidade` VALUES 
(1,'Acre','AC'),
(2,'Alagoas','AL'),
(3,'Amapá','AP'),
(4,'Amazonas','AM'),
(5,'Bahia','BA'),
(6,'Ceará','CE'),
(7,'Distrito Federal','DF'),
(8,'Espírito Santo','ES'),
(9,'Goiás','GO'),
(10,'Maranhão','MA'),
(11,'Mato Grosso', 'MT'),
(12,'Mato Grosso do Sul','MS'),
(13,'Minas Gerais','MG'),
(14,'Pará','PA'),
(15,'Paraíba','PB'),
(16,'Paraná','PR'),
(17,'Pernambuco','PE'),
(18,'Piauí','PI'),
(19,'Rio de Janeiro','RJ'),
(20,'Rio Grande do Norte','RN'),
(21,'Rio Grande do Sul','RS'),
(22,'Rondônia','RO'),
(23,'Roraima','RR'),
(24,'Santa Catarina','SC'),
(25,'São Paulo','SP'),
(26,'Sergipe','SE'),
(27,'Tocantins', 'TO'); 

INSERT INTO `fornecedor` VALUES 
(1, 25, 'Guitar Center Music', 'R. Serra de Bragança', '1593', 'Vila Gomes Cardim', '03309-001', 'Sr. Gomes', '58.574.853/0001-17', '686.281.728.179', '(11)3230-4021'),
(2, 6, 'Cordas e Violões', 'Av. Santos Dumont', '12', 'Centro', '60150-162', 'Manel Véi', '16.904.690/0001-43', '74291957-9', '(85)99888-0001');

INSERT INTO `produto` VALUES 
(1, 1, 1, 'Guitarra Tagima Hand Made Brasil', 9.400, false, 1),
(2, 1, 1, 'Guitarra Phx Strato Sunset St-h Bk', 7.900, false, 1),
(3, 2, 1, 'Baixo Tagima Classic Series Xb 31 5 Cordas', 8.100, false, 1),
(4, 6, 2, 'Amplificador Cubo Para Baixo Borne Cb80 Preto', 22.500, false, 1),
(5, 7, 2, 'Encordoamento Giannini Cobra 011 P/ Violão Aço', 0.100, false, 5);

INSERT INTO `transportadora` VALUES 
(1, 25, 'Jadlog', 'R. Ana Cintra', '65', 'Campos Eliseos', '01201-060', '04.884.082/0005-69', '652.667.980.895', 'Jadlog Administração', '(11)3213-1234'),
(2, 6, 'Braspress', 'Rod, Rod. 4º Anel Viário', '2700', 'Ancuri', '60874-212', '77.619.256/0001-06', '52606740-3', 'Braspress Administração', '(85)3499-6300');

INSERT INTO `loja` VALUES 
(1, 6, 'CENTRAL MUSIC', 'R. Pedro Pereira', '80', 'Centro', '(85)3799-7630', '56.981.180/0001-94', '00514036-6'),
(2, 6, 'R.Som Instrumentos', 'R. Pedro Pereira', '299', 'Centro', '(85)3226-6210', '13.370.635/0001-22', '15751212-6');

INSERT INTO `usuario` VALUES 
(1,'Admin','admin@email.com','$2y$10$jVVut0VuRr90kU8x1YfqqOa1vKa.gjAymLTauUNyWNozXJ6UxWIXq'),
(2,'Usuario','user@email.com','$2y$10$RUIrgFseRRJUkCYCrFqBgO/OnQHzGXtPGon4VNOE3PQO/bdlICHKW');