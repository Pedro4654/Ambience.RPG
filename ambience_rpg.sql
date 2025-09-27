-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para ambience_rpg
CREATE DATABASE IF NOT EXISTS `ambience_rpg` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ambience_rpg`;

-- Copiando estrutura para tabela ambience_rpg.acoes_reputacao
CREATE TABLE IF NOT EXISTS `acoes_reputacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `pontos` int NOT NULL,
  `descricao` text,
  `limite_diario` int DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.acoes_reputacao: ~10 rows (aproximadamente)
INSERT INTO `acoes_reputacao` (`id`, `nome`, `pontos`, `descricao`, `limite_diario`, `ativo`) VALUES
	(1, 'criar_conteudo', 10, 'Pontos por criar um novo conteúdo', 5, 1),
	(2, 'conteudo_aprovado', 25, 'Pontos quando um conteúdo é aprovado', NULL, 1),
	(3, 'receber_curtida', 2, 'Pontos por receber uma curtida', NULL, 1),
	(4, 'receber_comentario', 5, 'Pontos por receber um comentário', NULL, 1),
	(5, 'conteudo_baixado', 1, 'Pontos quando alguém baixa seu conteúdo', NULL, 1),
	(6, 'primeiro_login', 50, 'Pontos de boas-vindas no primeiro login', 1, 1),
	(7, 'login_diario', 5, 'Pontos por fazer login diário', 1, 1),
	(8, 'participar_sessao', 15, 'Pontos por participar de uma sessão', 3, 1),
	(9, 'mestrar_sessao', 30, 'Pontos por mestrar uma sessão', 2, 1),
	(10, 'criar_sala', 20, 'Pontos por criar uma nova sala', 2, 1);

-- Copiando estrutura para tabela ambience_rpg.arquivos
CREATE TABLE IF NOT EXISTS `arquivos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_original` varchar(255) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `caminho` varchar(500) NOT NULL,
  `tipo_mime` varchar(100) NOT NULL,
  `tamanho` int NOT NULL,
  `usuario_id` int NOT NULL,
  `data_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `arquivos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.arquivos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `descricao` text,
  `cor` varchar(7) DEFAULT NULL,
  `icone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.categorias: ~8 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `nome`, `descricao`, `cor`, `icone`) VALUES
	(1, 'D&D 5E', 'Dungeons & Dragons 5ª Edição', '#c41e3a', 'dice'),
	(2, 'Pathfinder', 'Sistema Pathfinder', '#8b4513', 'sword'),
	(3, 'RPG Nacional', 'Sistemas nacionais brasileiros', '#00ff00', 'flag'),
	(4, 'Cyberpunk', 'RPGs futuristas e cyberpunk', '#00ffff', 'circuit'),
	(5, 'Horror', 'RPGs de terror e suspense', '#800080', 'skull'),
	(6, 'Fantasia', 'RPGs de fantasia medieval', '#ffd700', 'castle'),
	(7, 'Ficção Científica', 'RPGs espaciais e sci-fi', '#4169e1', 'rocket'),
	(8, 'Genérico', 'Conteúdos que servem para vários sistemas', '#808080', 'gear');

-- Copiando estrutura para tabela ambience_rpg.comentarios
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conteudo_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `comentario` text NOT NULL,
  `avaliacao` int DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `idx_comentarios_conteudo` (`conteudo_id`),
  CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_chk_1` CHECK (((`avaliacao` >= 1) and (`avaliacao` <= 5)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.comentarios: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.conquistas
CREATE TABLE IF NOT EXISTS `conquistas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `icone_url` varchar(255) DEFAULT NULL,
  `condicao_json` json NOT NULL,
  `pontos_bonus` int DEFAULT '0',
  `raridade` enum('comum','incomum','raro','epico','lendario') DEFAULT 'comum',
  `ativo` tinyint(1) DEFAULT '1',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.conquistas: ~5 rows (aproximadamente)
INSERT INTO `conquistas` (`id`, `nome`, `descricao`, `icone_url`, `condicao_json`, `pontos_bonus`, `raridade`, `ativo`, `data_criacao`) VALUES
	(1, 'Criador Iniciante', 'Crie seu primeiro conteúdo', NULL, '{"tipo": "conteudos_criados", "quantidade": 1}', 50, 'comum', 1, '2025-09-04 18:05:05'),
	(2, 'Criador Ativo', 'Crie 10 conteúdos', NULL, '{"tipo": "conteudos_criados", "quantidade": 10}', 200, 'incomum', 1, '2025-09-04 18:05:05'),
	(3, 'Mestre Dedicado', 'Mestre 5 sessões', NULL, '{"tipo": "sessoes_mestradas", "quantidade": 5}', 300, 'raro', 1, '2025-09-04 18:05:05'),
	(4, 'Popular', 'Receba 100 curtidas', NULL, '{"tipo": "curtidas_recebidas", "quantidade": 100}', 500, 'epico', 1, '2025-09-04 18:05:05'),
	(5, 'Lenda', 'Alcance 10.000 pontos de reputação', NULL, '{"tipo": "pontos_reputacao", "quantidade": 10000}', 1000, 'lendario', 1, '2025-09-04 18:05:05');

-- Copiando estrutura para tabela ambience_rpg.conteudos
CREATE TABLE IF NOT EXISTS `conteudos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descricao` text,
  `tipo` enum('ficha_personagem','item','dado','token','movel','objeto','grid','outros') NOT NULL,
  `criador_id` int NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `sala_id` int DEFAULT NULL,
  `dados_json` json DEFAULT NULL,
  `imagem_url` varchar(255) DEFAULT NULL,
  `publico` tinyint(1) DEFAULT '1',
  `aprovado` tinyint(1) DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visualizacoes` int DEFAULT '0',
  `downloads` int DEFAULT '0',
  `curtidas` int DEFAULT '0',
  `avaliacao_media` decimal(3,2) DEFAULT '0.00',
  `total_avaliacoes` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `sala_id` (`sala_id`),
  KEY `idx_conteudos_tipo` (`tipo`),
  KEY `idx_conteudos_criador` (`criador_id`),
  KEY `idx_conteudos_publico` (`publico`),
  KEY `idx_conteudos_data` (`data_criacao`),
  KEY `idx_conteudos_aprovado` (`aprovado`),
  FULLTEXT KEY `idx_conteudos_busca` (`titulo`,`descricao`),
  CONSTRAINT `conteudos_ibfk_1` FOREIGN KEY (`criador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conteudos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `conteudos_ibfk_3` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.conteudos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.conteudo_tags
CREATE TABLE IF NOT EXISTS `conteudo_tags` (
  `conteudo_id` int NOT NULL,
  `tag_id` int NOT NULL,
  PRIMARY KEY (`conteudo_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `conteudo_tags_ibfk_1` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conteudo_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.conteudo_tags: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.convites_sala
CREATE TABLE IF NOT EXISTS `convites_sala` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sala_id` int NOT NULL,
  `remetente_id` int NOT NULL,
  `destinatario_id` int NOT NULL,
  `token` varchar(100) NOT NULL,
  `status` enum('pendente','aceito','recusado','expirado') DEFAULT 'pendente',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_expiracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `sala_id` (`sala_id`),
  KEY `remetente_id` (`remetente_id`),
  KEY `destinatario_id` (`destinatario_id`),
  CONSTRAINT `convites_sala_ibfk_1` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `convites_sala_ibfk_2` FOREIGN KEY (`remetente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `convites_sala_ibfk_3` FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.convites_sala: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.curtidas
CREATE TABLE IF NOT EXISTS `curtidas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `conteudo_id` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_curtida` (`usuario_id`,`conteudo_id`),
  KEY `idx_curtidas_conteudo` (`conteudo_id`),
  KEY `idx_curtidas_usuario` (`usuario_id`),
  CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.curtidas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.estatisticas_uso
CREATE TABLE IF NOT EXISTS `estatisticas_uso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `conteudos_criados` int DEFAULT '0',
  `conteudos_baixados` int DEFAULT '0',
  `sessoes_participadas` int DEFAULT '0',
  `sessoes_mestradas` int DEFAULT '0',
  `tempo_total_jogado` int DEFAULT '0',
  `data_ultima_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `estatisticas_uso_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.estatisticas_uso: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.favoritos
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `conteudo_id` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_favorito` (`usuario_id`,`conteudo_id`),
  KEY `conteudo_id` (`conteudo_id`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.favoritos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.fichas_personagem
CREATE TABLE IF NOT EXISTS `fichas_personagem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conteudo_id` int NOT NULL,
  `nome_personagem` varchar(100) NOT NULL,
  `classe` varchar(50) DEFAULT NULL,
  `raca` varchar(50) DEFAULT NULL,
  `nivel` int DEFAULT '1',
  `atributos` json DEFAULT NULL,
  `habilidades` json DEFAULT NULL,
  `equipamentos` json DEFAULT NULL,
  `historia` text,
  PRIMARY KEY (`id`),
  KEY `conteudo_id` (`conteudo_id`),
  FULLTEXT KEY `idx_fichas_busca` (`nome_personagem`,`historia`),
  CONSTRAINT `fichas_personagem_ibfk_1` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.fichas_personagem: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.grids
CREATE TABLE IF NOT EXISTS `grids` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conteudo_id` int NOT NULL,
  `nome_grid` varchar(100) NOT NULL,
  `largura` int NOT NULL,
  `altura` int NOT NULL,
  `tamanho_celula` int DEFAULT '30',
  `imagem_fundo` varchar(255) DEFAULT NULL,
  `configuracoes` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conteudo_id` (`conteudo_id`),
  CONSTRAINT `grids_ibfk_1` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.grids: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.historico_buscas
CREATE TABLE IF NOT EXISTS `historico_buscas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `termo_busca` varchar(255) NOT NULL,
  `tipo_conteudo` varchar(50) DEFAULT NULL,
  `resultados_encontrados` int DEFAULT NULL,
  `data_busca` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `historico_buscas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.historico_buscas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.historico_dados
CREATE TABLE IF NOT EXISTS `historico_dados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sessao_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `tipo_dado` varchar(20) NOT NULL,
  `quantidade` int NOT NULL,
  `resultados` json NOT NULL,
  `modificador` int DEFAULT '0',
  `data_jogada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sessao_id` (`sessao_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `historico_dados_ibfk_1` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historico_dados_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.historico_dados: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.historico_reputacao
CREATE TABLE IF NOT EXISTS `historico_reputacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `acao_id` int NOT NULL,
  `pontos_ganhos` int NOT NULL,
  `referencia_id` int DEFAULT NULL,
  `referencia_tipo` varchar(50) DEFAULT NULL,
  `data_acao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `acao_id` (`acao_id`),
  KEY `idx_historico_reputacao_usuario` (`usuario_id`),
  KEY `idx_historico_reputacao_data` (`data_acao`),
  CONSTRAINT `historico_reputacao_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historico_reputacao_ibfk_2` FOREIGN KEY (`acao_id`) REFERENCES `acoes_reputacao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.historico_reputacao: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.itens
CREATE TABLE IF NOT EXISTS `itens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conteudo_id` int NOT NULL,
  `nome_item` varchar(100) NOT NULL,
  `tipo_item` enum('arma','armadura','acessorio','consumivel','outros') NOT NULL,
  `raridade` enum('comum','incomum','raro','epico','lendario') DEFAULT 'comum',
  `preco` decimal(10,2) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `propriedades` json DEFAULT NULL,
  `efeitos` text,
  PRIMARY KEY (`id`),
  KEY `conteudo_id` (`conteudo_id`),
  FULLTEXT KEY `idx_itens_busca` (`nome_item`,`efeitos`),
  CONSTRAINT `itens_ibfk_1` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.itens: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.logs_sistema
CREATE TABLE IF NOT EXISTS `logs_sistema` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `acao` varchar(100) NOT NULL,
  `detalhes` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `data_acao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `logs_sistema_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.logs_sistema: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.mensagens_chat
CREATE TABLE IF NOT EXISTS `mensagens_chat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sala_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `mensagem` text NOT NULL,
  `tipo` enum('texto','dado','sistema','acao') DEFAULT 'texto',
  `dados_extras` json DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sala_id` (`sala_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `mensagens_chat_ibfk_1` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mensagens_chat_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.mensagens_chat: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela ambience_rpg.migrations: ~5 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_09_05_104430_add_remember_token_to_usuarios_table', 1),
	(5, '2025_09_05_134625_add_remembert_token_to_usuario_table', 1);

-- Copiando estrutura para tabela ambience_rpg.notificacoes
CREATE TABLE IF NOT EXISTS `notificacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `tipo` enum('comentario','convite_sala','sessao_iniciada','conteudo_aprovado','curtida','novo_seguidor','ranking_mudou','outros') NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `lida` tinyint(1) DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notificacoes_usuario` (`usuario_id`),
  KEY `idx_notificacoes_lida` (`lida`),
  CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.notificacoes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.objetos_grid
CREATE TABLE IF NOT EXISTS `objetos_grid` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sessao_id` int NOT NULL,
  `conteudo_id` int DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `posicao_x` int NOT NULL,
  `posicao_y` int NOT NULL,
  `largura_casas` int DEFAULT '1',
  `altura_casas` int DEFAULT '1',
  `rotacao` int DEFAULT '0',
  `escala` decimal(3,2) DEFAULT '1.00',
  `visivel` tinyint(1) DEFAULT '1',
  `cor` varchar(7) DEFAULT NULL,
  `propriedades` json DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sessao_id` (`sessao_id`),
  KEY `conteudo_id` (`conteudo_id`),
  CONSTRAINT `objetos_grid_ibfk_1` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `objetos_grid_ibfk_2` FOREIGN KEY (`conteudo_id`) REFERENCES `conteudos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.objetos_grid: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.participantes_sala
CREATE TABLE IF NOT EXISTS `participantes_sala` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sala_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `papel` enum('membro','mestre','admin_sala') DEFAULT 'membro',
  `data_entrada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_sala` (`sala_id`,`usuario_id`),
  KEY `idx_participantes_sala` (`sala_id`),
  KEY `idx_participantes_usuario` (`usuario_id`),
  CONSTRAINT `participantes_sala_ibfk_1` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participantes_sala_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.participantes_sala: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.participantes_sessao
CREATE TABLE IF NOT EXISTS `participantes_sessao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sessao_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `ficha_personagem_id` int DEFAULT NULL,
  `posicao_x` int DEFAULT NULL,
  `posicao_y` int DEFAULT NULL,
  `data_entrada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sessao_id` (`sessao_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `ficha_personagem_id` (`ficha_personagem_id`),
  CONSTRAINT `participantes_sessao_ibfk_1` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participantes_sessao_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participantes_sessao_ibfk_3` FOREIGN KEY (`ficha_personagem_id`) REFERENCES `fichas_personagem` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.participantes_sessao: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela ambience_rpg.password_reset_tokens: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.permissoes_sala
CREATE TABLE IF NOT EXISTS `permissoes_sala` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sala_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `pode_criar_conteudo` tinyint(1) DEFAULT '1',
  `pode_editar_grid` tinyint(1) DEFAULT '0',
  `pode_iniciar_sessao` tinyint(1) DEFAULT '0',
  `pode_moderar_chat` tinyint(1) DEFAULT '0',
  `pode_convidar_usuarios` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_permissao` (`sala_id`,`usuario_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `permissoes_sala_ibfk_1` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permissoes_sala_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.permissoes_sala: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.salas
CREATE TABLE IF NOT EXISTS `salas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `criador_id` int NOT NULL,
  `imagem_url` varchar(255) DEFAULT NULL,
  `tipo` enum('publica','privada','apenas_convite') DEFAULT 'publica',
  `senha_hash` varchar(255) DEFAULT NULL,
  `max_participantes` int DEFAULT '50',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ativa` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `criador_id` (`criador_id`),
  CONSTRAINT `salas_ibfk_1` FOREIGN KEY (`criador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.salas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.seguidores
CREATE TABLE IF NOT EXISTS `seguidores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `seguidor_id` int NOT NULL,
  `seguido_id` int NOT NULL,
  `data_inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_seguidor` (`seguidor_id`,`seguido_id`),
  KEY `idx_seguidores_seguido` (`seguido_id`),
  KEY `idx_seguidores_seguidor` (`seguidor_id`),
  CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`seguidor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`seguido_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.seguidores: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.sessoes_jogo
CREATE TABLE IF NOT EXISTS `sessoes_jogo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sala_id` int NOT NULL,
  `grid_id` int DEFAULT NULL,
  `nome_sessao` varchar(100) NOT NULL,
  `mestre_id` int NOT NULL,
  `data_inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_fim` timestamp NULL DEFAULT NULL,
  `status` enum('preparando','ativa','pausada','finalizada') DEFAULT 'preparando',
  `configuracoes` json DEFAULT NULL,
  `backup_grid` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grid_id` (`grid_id`),
  KEY `mestre_id` (`mestre_id`),
  KEY `idx_sessoes_sala` (`sala_id`),
  KEY `idx_sessoes_status` (`status`),
  CONSTRAINT `sessoes_jogo_ibfk_1` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sessoes_jogo_ibfk_2` FOREIGN KEY (`grid_id`) REFERENCES `grids` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sessoes_jogo_ibfk_3` FOREIGN KEY (`mestre_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.sessoes_jogo: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.tags
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cor` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.tags: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.templates_grid
CREATE TABLE IF NOT EXISTS `templates_grid` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grid_id` int NOT NULL,
  `nome_template` varchar(100) NOT NULL,
  `criador_id` int NOT NULL,
  `objetos_salvos` json NOT NULL,
  `descricao` text,
  `publico` tinyint(1) DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `downloads` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `grid_id` (`grid_id`),
  KEY `criador_id` (`criador_id`),
  CONSTRAINT `templates_grid_ibfk_1` FOREIGN KEY (`grid_id`) REFERENCES `grids` (`id`) ON DELETE CASCADE,
  CONSTRAINT `templates_grid_ibfk_2` FOREIGN KEY (`criador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.templates_grid: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela ambience_rpg.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `nome_completo` varchar(100) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `bio` text,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_ultimo_login` timestamp NULL DEFAULT NULL,
  `status` enum('ativo','inativo','banido') DEFAULT 'ativo',
  `nivel_usuario` enum('usuario','moderador','admin') DEFAULT 'usuario',
  `verificado` tinyint(1) DEFAULT '0',
  `pontos_reputacao` int DEFAULT '0',
  `ranking_posicao` int DEFAULT '0',
  `titulo_personalizado` varchar(100) DEFAULT NULL,
  `emblema_url` varchar(255) DEFAULT NULL,
  `data_de_nascimento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_usuarios_reputacao` (`pontos_reputacao`),
  KEY `idx_usuarios_ranking` (`ranking_posicao`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `username`, `nickname`, `email`, `senha_hash`, `remember_token`, `nome_completo`, `avatar_url`, `bio`, `data_criacao`, `data_ultimo_login`, `status`, `nivel_usuario`, `verificado`, `pontos_reputacao`, `ranking_posicao`, `titulo_personalizado`, `emblema_url`, `data_de_nascimento`) VALUES
	(1, 'Pedro viado', 'Apelido', 'Pedroviado@edwdaw', '$2y$12$q98cBSkXUk0l/dhqHFkkhOCBFbepux2CHEUH4bWG3IvKzMlXgsWZe', NULL, NULL, NULL, NULL, '2025-09-04 21:10:49', NULL, 'ativo', 'usuario', 0, 0, 0, NULL, NULL, '1444-05-23'),
	(2, 'Pedro viado22', 'Apelido', 'Pedroviado@edwdaws', '$2y$12$W7i5omZTVejZFqK0MPS6au2HlyfPmNz23nLCTIiJFW9KZbP.Psng.', NULL, NULL, NULL, NULL, '2025-09-04 21:14:14', NULL, 'inativo', 'usuario', 0, 0, 0, NULL, NULL, '1000-05-23'),
	(3, 'pedraogayzao', 'viado', 'pedraogayzaoviadao@etec', '$2y$12$e/Db9lnDB.R4K5VLjhyNWuW2emARxJYNDiX/jiUaQ3kGxgFkkM6Vi', 'S7uxYLrwLlX4T0PfUuRndg3KS5ofHb2SyDg43GdkdW5uv6NsVjS6vZKELp2w', NULL, NULL, NULL, '2025-09-05 16:55:36', NULL, 'ativo', 'usuario', 0, 0, 0, NULL, NULL, '1992-02-02');

-- Copiando estrutura para tabela ambience_rpg.usuario_conquistas
CREATE TABLE IF NOT EXISTS `usuario_conquistas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `conquista_id` int NOT NULL,
  `data_desbloqueio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `progresso_atual` int DEFAULT '0',
  `progresso_total` int DEFAULT '1',
  `desbloqueada` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_usuario_conquista` (`usuario_id`,`conquista_id`),
  KEY `conquista_id` (`conquista_id`),
  CONSTRAINT `usuario_conquistas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuario_conquistas_ibfk_2` FOREIGN KEY (`conquista_id`) REFERENCES `conquistas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela ambience_rpg.usuario_conquistas: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
