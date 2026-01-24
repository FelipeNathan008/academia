-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`aluno`
-- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `mydb`.`aluno` (
    `id_aluno` INT NOT NULL AUTO_INCREMENT,
    `aluno_nome` VARCHAR(120) NOT NULL,
    `aluno_nascimento` DATE NOT NULL,
    `aluno_desc` VARCHAR(120) NOT NULL,
  `aluno_foto` MEDIUMBLOB NOT NULL,
  PRIMARY KEY (`id_aluno`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`responsavel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`responsavel` (
  `id_responsavel` INT NOT NULL AUTO_INCREMENT,
  `aluno_id_aluno` INT NOT NULL,
  `resp_nome` VARCHAR(120) NOT NULL,
  `resp_parentesco` VARCHAR(60) NOT NULL,
  `resp_cpf` VARCHAR(11) NOT NULL,
  `resp_logradouro` VARCHAR(150) NOT NULL,
  `resp_bairro` VARCHAR(150) NOT NULL,
  `resp_cidade` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_responsavel`),
  INDEX `fk_responsavel_aluno_idx` (`aluno_id_aluno` ASC) VISIBLE,
  CONSTRAINT `fk_responsavel_aluno`
    FOREIGN KEY (`aluno_id_aluno`)
    REFERENCES `mydb`.`aluno` (`id_aluno`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`modalidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`modalidade` (
  `id_modalidade` INT NOT NULL AUTO_INCREMENT,
  `mod_nome` VARCHAR(100) NOT NULL,
  `mod_desc` TEXT NOT NULL,
  PRIMARY KEY (`id_modalidade`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `user_nome` VARCHAR(45) NOT NULL,
  `user_email` VARCHAR(45) NOT NULL,
  `user_password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_user`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`matricula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`matricula` (
  `id_matricula` INT NOT NULL AUTO_INCREMENT,
  `aluno_id_aluno` INT NOT NULL,
  `matri_desc` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_matricula`),
  INDEX `fk_matricula_aluno1_idx` (`aluno_id_aluno` ASC) VISIBLE,
  CONSTRAINT `fk_matricula_aluno1`
    FOREIGN KEY (`aluno_id_aluno`)
    REFERENCES `mydb`.`aluno` (`id_aluno`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`professor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`professor` (
  `id_professor` INT NOT NULL AUTO_INCREMENT,
  `prof_nome` VARCHAR(120) NOT NULL,
  `prof_nascimento` DATE NOT NULL,
  `prof_desc` VARCHAR(150) NOT NULL,
  `prof_foto` MEDIUMBLOB NOT NULL,
  PRIMARY KEY (`id_professor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`grade_horario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`grade_horario` (
  `id_grade` INT NOT NULL AUTO_INCREMENT,
  `professor_id_professor` INT NOT NULL,
  `grade_modalidade` INT NOT NULL,
  `grade_dia_semana` VARCHAR(80) NOT NULL,
  `grade_inicio` TIME NOT NULL,
  `grade_fim` TIME NOT NULL,
  `grade_desc` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_grade`),
  INDEX `fk_horario_professor_professor1_idx` (`professor_id_professor` ASC) VISIBLE,
  CONSTRAINT `fk_horario_professor_professor1`
    FOREIGN KEY (`professor_id_professor`)
    REFERENCES `mydb`.`professor` (`id_professor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`graduacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`graduacao` (
  `id_graduacao` INT NOT NULL AUTO_INCREMENT,
  `gradu_nome_cor` VARCHAR(80) NOT NULL,
  `gradu_grau` INT NOT NULL,
  PRIMARY KEY (`id_graduacao`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`detalhes_professor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`detalhes_professor` (
  `id_det_professor` INT NOT NULL AUTO_INCREMENT,
  `professor_id_professor` INT NOT NULL,
  `det_gradu_nome_cor` VARCHAR(80) NOT NULL,
  `det_grau` INT NOT NULL,
  `det_modalidade` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_det_professor`),
  INDEX `fk_detalhes_professor_professor1_idx` (`professor_id_professor` ASC) VISIBLE,
  CONSTRAINT `fk_detalhes_professor_professor1`
    FOREIGN KEY (`professor_id_professor`)
    REFERENCES `mydb`.`professor` (`id_professor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`detalhes_matricula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`detalhes_matricula` (
  `id_det_matricula` INT NOT NULL AUTO_INCREMENT,
  `matricula_id_matricula` INT NOT NULL,
  `modalidade_id_modalidade` INT NOT NULL,
  `grade_horario_id_grade` INT NOT NULL,
  PRIMARY KEY (`id_det_matricula`),
  INDEX `fk_detalhes_matricula_matricula1_idx` (`matricula_id_matricula` ASC) VISIBLE,
  INDEX `fk_detalhes_matricula_modalidade1_idx` (`modalidade_id_modalidade` ASC) VISIBLE,
  INDEX `fk_detalhes_matricula_grade_horario1_idx` (`grade_horario_id_grade` ASC) VISIBLE,
  CONSTRAINT `fk_detalhes_matricula_matricula1`
    FOREIGN KEY (`matricula_id_matricula`)
    REFERENCES `mydb`.`matricula` (`id_matricula`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalhes_matricula_modalidade1`
    FOREIGN KEY (`modalidade_id_modalidade`)
    REFERENCES `mydb`.`modalidade` (`id_modalidade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalhes_matricula_grade_horario1`
    FOREIGN KEY (`grade_horario_id_grade`)
    REFERENCES `mydb`.`grade_horario` (`id_grade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`valor_aula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`valor_aula` (
  `id_valor_aula` INT NOT NULL AUTO_INCREMENT,
  `modalidade_id` INT NOT NULL,
  `valor_aula` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_valor_aula`),
  INDEX `fk_preco_aula_modalidade1_idx` (`modalidade_id` ASC) VISIBLE,
  CONSTRAINT `fk_preco_aula_modalidade1`
    FOREIGN KEY (`modalidade_id`)
    REFERENCES `mydb`.`modalidade` (`id_modalidade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`mensalidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`mensalidade` (
  `id_mensalidade` INT NOT NULL AUTO_INCREMENT,
  `aluno_id_aluno` INT NOT NULL,
  `mensa_periodo_vigente` VARCHAR(60) NOT NULL,
  `mensa_data_venc` DATE NOT NULL,
  `mensa_valor` DECIMAL(10,2) NOT NULL,
  `mensa_status` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_mensalidade`),
  INDEX `fk_mensalidade_aluno1_idx` (`aluno_id_aluno` ASC) VISIBLE,
  CONSTRAINT `fk_mensalidade_aluno1`
    FOREIGN KEY (`aluno_id_aluno`)
    REFERENCES `mydb`.`aluno` (`id_aluno`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`frequencia_aluno`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`frequencia_aluno` (
  `id_frequencia_aluno` INT NOT NULL AUTO_INCREMENT,
  `grade_horario_id_grade` INT NOT NULL,
  `freq_alunos` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_frequencia_aluno`),
  INDEX `fk_frequencia_aluno_grade_horario1_idx` (`grade_horario_id_grade` ASC) VISIBLE,
  CONSTRAINT `fk_frequencia_aluno_grade_horario1`
    FOREIGN KEY (`grade_horario_id_grade`)
    REFERENCES `mydb`.`grade_horario` (`id_grade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`detalhes_mensalidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`detalhes_mensalidade` (
  `id_detalhes_mensalidade` INT NOT NULL AUTO_INCREMENT,
  `mensalidade_id_mensalidade` INT NOT NULL,
  `det_mensa_forma_pagamento` VARCHAR(60) NOT NULL,
  `det_mensa_per_vig_pago` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_detalhes_mensalidade`),
  INDEX `fk_detalhes_mensalidade_mensalidade1_idx` (`mensalidade_id_mensalidade` ASC) VISIBLE,
  CONSTRAINT `fk_detalhes_mensalidade_mensalidade1`
    FOREIGN KEY (`mensalidade_id_mensalidade`)
    REFERENCES `mydb`.`mensalidade` (`id_mensalidade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
