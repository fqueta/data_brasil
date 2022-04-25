-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Abr-2022 às 23:02
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `data_brasil`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `documentos`
--

CREATE TABLE `documentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` enum('s','n') COLLATE utf8mb4_unicode_ci NOT NULL,
  `autor` int(11) DEFAULT NULL,
  `conteudo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excluido` enum('n','s') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_excluido` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletado` enum('n','s') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_deletado` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `documentos`
--

INSERT INTO `documentos` (`id`, `created_at`, `updated_at`, `token`, `nome`, `url`, `tipo`, `ativo`, `autor`, `conteudo`, `excluido`, `reg_excluido`, `deletado`, `reg_deletado`) VALUES
(1, NULL, '2022-04-23 13:57:03', NULL, 'Cabeçalho', 'cabecario-lista-beneficiario', 'html', 's', 1, '<p>\r\n                </p><table width=\"662\" cellspacing=\"0\" cellpadding=\"7\">\r\n                    <colgroup><col width=\"142\">\r\n\r\n                    <col width=\"492\">\r\n\r\n                    </colgroup><tbody><tr valign=\"top\">\r\n                        <td style=\"background: transparent\" width=\"142\" height=\"80\"><img src=\"/storage/documentos/logo_prefeitura.png\" style=\"width: 100%;\">\r\n                        <br></td>\r\n                        <td style=\"background: transparent\" width=\"492\"><p style=\"margin-bottom: 0.35cm; orphans: 0; widows: 0\" align=\"center\">\r\n                            <font face=\"Arial, serif\"><b>MUNICÍPIO DE CONCEIÇÃO DO MATO\r\n                            DENTRO<br>\r\n                </b>Rua Daniel de Carvalho, 161, Centro – CEP\r\n                            35.860-000</font></p>\r\n                            <p style=\"orphans: 0; widows: 0\" align=\"center\"><font face=\"Arial, serif\">ESTADO\r\n                            DE MINAS GERAIS</font></p>\r\n                        </td>\r\n                    </tr>\r\n                </tbody></table>', 'n', NULL, 'n', NULL),
(2, NULL, '2022-04-23 14:18:07', NULL, 'Lista de beneficiário parte1', 'lista-beneficiario', 'html', 's', 1, '<p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\"><a name=\"_Hlk82675389\"></a>\r\n<font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>{lote}-\r\nBENEFICIÁRIO(A): {tipo_beneficiario}:</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">\r\n</font></font>{dados_beneficiario} residente e domiciliado na {endereco}, n°{numero},\r\nBairro {bairro}, no município de {cidade}-{uf}, CEP: {cep}.</p><div><font face=\"Arial Narrow, serif\"></font></div><p></p>', 'n', NULL, 'n', NULL),
(3, NULL, '2022-04-23 14:05:34', NULL, 'Beneficiario com parceiro', 'lista-beneficiario-2', 'html', 's', 1, '<font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>{nome_beneficiario}</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">,\r\n{nacionalidade}, {estado_civil}, {profissao}, {nascida} aos {nascimento}, {filha de}\r\n{pai} e {mae}, RG:\r\n{rg}, CPF: {cpf} </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">e</font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">\r\n{seu companheiro}</font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>\r\n{nome_conjuge}, </b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">{nacionalidade_conjuge},\r\n</font></font><font color=\"#000000\"><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">{estado_civil_conjuge},\r\n{profissao_conjuge}</font></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">,\r\nnascido aos {nascimento_conjuge},</font></font><font color=\"#ff0000\"><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">\r\n</font></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">filho\r\nde {pai_conjuge} e {mae_conjuge}, RG: {rg_conjuge},\r\nCPF: {cpf_conjuge}, vivendo em união estável desde {data_uniao}, <br></font></font>', 'n', NULL, 'n', NULL),
(4, NULL, NULL, NULL, 'Lista de beneficiário Prefeitura', 'lista-beneficiario-prefeitura', 'html', 's', NULL, '<p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\">\n                <font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>{lote}</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">-\n                </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>BENEFICIÁRIO\n                (A): Município de Conceição do Mato Dentro/MG, </b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">pessoa\n                Jurídica de Direito Público Interno, inscrita no CNPJ:\n                18.303.156/0001-07, com sede à Rua Daniel de Carvalho, nº 161,\n                Centro, nesta cidade e comarca Conceição do Mato Dentro/MG, CEP:\n                35.680-000, representado por José Fernando Aparecido de Oliveira,\n                brasileiro, casado, prefeito, portador do RG: M-3.618.630, inscrito\n                no CPF sob o nº 032.412.426-09, residente e domiciliado na Rua Raul\n                Soares, nº 253, Bairro centro, Conceição do Mato Dentro, CEP:\n                35.680-000.</font></font></p>\n                <p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\"><br>\n\n                </p>\n                <p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\"><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>IMÓVEL</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">:\n                LOTE {lote} ({lote_extenso}) – QUADRA {quadra} ({quadra_extenso}), conforme memorial\n                descritivo do PRF.  </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>Valor\n                Lote</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">:\n                </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>R$</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">\n                </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>{valor}</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">\n                 </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>Valor\n                Edificação</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">:\n                </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>R$</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">\n                </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>{valor_edificacao}.</b></font></font></p>\n                <p></p>', 's', NULL, 'n', NULL),
(5, '2022-04-22 13:56:33', '2022-04-23 14:05:53', '6262b339ddda8', 'Beneficiario sem parceiro', 'lista-beneficiario-3', 'html', 's', 1, '<font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>{nome_beneficiario}</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">,\r\n{nacionalidade}, {estado_civil}, {profissao}, {nascida} aos {nascimento}, {filha de}\r\n{pai} e {mae}, RG:\r\n{rg}, CPF: {cpf},</font></font>', 'n', NULL, 'n', NULL),
(6, '2022-04-23 07:23:34', '2022-04-23 14:18:51', '6263a956d8e63', 'Lote sem beneficiario', 'lote-sem-beneficiario', 'html', 's', 1, '<p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\">\r\n      <font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>{lote}.\r\n      BENEFICIÁRIO(A):</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">\r\n      </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>Município\r\n      de Conceição do Mato Dentro/MG, </b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">pessoa\r\n      Jurídica de Direito Público Interno, inscrita no CNPJ:\r\n      18.303.156/0001-07, com sede à Rua Daniel de Carvalho, nº 161,\r\n      Centro, nesta cidade e comarca Conceição do Mato Dentro/MG, CEP:\r\n      35.680-000, representado por José Fernando Aparecido de Oliveira,\r\n      brasileiro, casado, prefeito, portador do RG: M-3.618.630, inscrito\r\n      no CPF sob o nº 032.412.426-09, residente e domiciliado na Rua Raul\r\n      Soares, nº 253, Bairro centro, Conceição do Mato Dentro, CEP:\r\n      35.680-000.</font></font></p>\r\n      <p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\"><br>\r\n      \r\n      </p>\r\n      <p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\"><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>IMÓVEL</b></font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">:\r\n      LOTE {lote}  ({lote_extenso} ) – QUADRA </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">{quadra}\r\n      ({quadra_extenso}),   </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\">conforme\r\n      memorial descritivo do PRF.  </font></font><font face=\"Arial Narrow, serif\"><font style=\"font-size: 12pt\" size=\"3\"><b>Valor\r\n      Lote: R$ {valor_lote}.   Valor Edificação: R$ {valor_edificacao}.</b></font></font></p>\r\n      <p style=\"margin-bottom: 0cm; line-height: 100%\" align=\"justify\"><font face=\"Arial Narrow, serif\"></font></p>\r\n      <p></p>', 'n', NULL, 'n', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `qoptions`
--

CREATE TABLE `qoptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `painel` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` enum('s','n') COLLATE utf8mb4_unicode_ci NOT NULL,
  `excluido` enum('n','s') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_excluido` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletado` enum('n','s') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_deletado` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `qoptions`
--

INSERT INTO `qoptions` (`id`, `created_at`, `updated_at`, `token`, `nome`, `url`, `valor`, `obs`, `painel`, `ativo`, `excluido`, `reg_excluido`, `deletado`, `reg_deletado`) VALUES
(1, NULL, NULL, NULL, 'Cadastro do Município', 'cad_municipio', '{\n                    \"municipio\":{\n                        \"razao\":\"Município de Conceição do Mato Dentro/MG\",\n                        \"tipo\":\"pessoa Jurídica de Direito Público Interno\",\n                        \"cnpj\":\"18.303.156/0001-07\",\n                        \"endereco\":\"Rua Daniel de Carvalho\",\n                        \"numero\":\"161\",\n                        \"bairro\":\"Centro\",\n                        \"cidade\":\"Conceição do Mato Dentro\",\n                        \"cep\":\"35.680-000\"\n                    },\n                    \"representante\":{\n                        \"cargo\":\"prefeito\",\n                        \"nome\":\"José Fernando Aparecido de Oliveira\",\n                        \"nacionalidade\":\"brasileiro\",\n                        \"estado_civil\":\"casado\",\n                        \"rg\":\"M-3.618.630\",\n                        \"cpf\":\"032.412.426-09\",\n                        \"endereco\":\"Rua Raul Soares\",\n                        \"numero\":\"253\",\n                        \"bairro\":\"Centro\",\n                        \"cidade\":\"Conceição do Mato Dentro\",\n                        \"cep\":\"35.680-000\"\n                    }\n                }', NULL, NULL, 's', 'n', NULL, 'n', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `qoptions`
--
ALTER TABLE `qoptions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `qoptions`
--
ALTER TABLE `qoptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
