<?php

   class Database {
		
		public function connectDB($server, $DBname, $serverLogin, $serverPassword){
			$connectionResult = mysql_connect($server,$serverLogin,$serverPassword);
			mysql_select_db($DBname);
		}
		
		public function getRankingReclamacoes($CNAESetor, $UF){
			$queryRanking = "SELECT strNomeFantasia, COUNT(*) AS qtd FROM Reclamacao WHERE strNomeFantasia IS NOT NULL AND strNomeFantasia <> 'NULL' ";
			
			if($CNAESetor != null){
				$queryRanking .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryRanking .= " AND UF = '" . $UF . "' ";
			}
			
			$queryRanking .= " GROUP BY strNomeFantasia ORDER BY COUNT(*) DESC LIMIT 5 ";
			
			$results = mysql_query($queryRanking);
			
			$ranking = array();
			
			for($i = 0; $i< mysql_num_rows($results); $i++){
				$ranking[$i]['NomeEmpresa'] = utf8_decode(mysql_result($results, $i,'strNomeFantasia'));
				$ranking[$i]['qtd'] = mysql_result($results, $i,'qtd');
		    }
			
			return $ranking;
		}
		
		public function getRankingNaoAtendidas($CNAESetor, $UF){
			$queryRanking = "SELECT strNomeFantasia, COUNT(*) AS qtd FROM Reclamacao WHERE strNomeFantasia IS NOT NULL AND 
																		  strNomeFantasia <> 'NULL' AND
																		  atendida = 'N' ";
			
			if($CNAESetor != null){
				$queryRanking .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryRanking .= " AND UF = '" . $UF . "' ";
			}
			
			$queryRanking .= " GROUP BY strNomeFantasia ORDER BY COUNT(*) DESC LIMIT 5 ";
			
			$results = mysql_query($queryRanking);
			
			$ranking = array();
			
			for($i = 0; $i< mysql_num_rows($results); $i++){
				$ranking[$i]['NomeEmpresa'] = utf8_decode(mysql_result($results, $i,'strNomeFantasia'));
				$ranking[$i]['qtd'] = mysql_result($results, $i,'qtd');
		    }
			
			return $ranking;
		}
		
		public function getQtdAtendidasNaoAtendidas($CNAESetor, $UF){
			$queryReclamacoes = "SELECT Atendida, COUNT(*) AS qtd FROM Reclamacao WHERE 1=1 ";
			
			if($CNAESetor != null){
				$queryReclamacoes .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryReclamacoes .= " AND UF = '" . $UF . "' ";
			}
			
			$queryReclamacoes .= " GROUP BY Atendida ORDER BY Atendida DESC";
			
			$results = mysql_query($queryReclamacoes);
			$qtdReclamacoes = array();
			
			for($i = 0; $i< mysql_num_rows($results); $i++){
				if(mysql_result($results, $i,'Atendida') == 'S') {
					$qtdReclamacoes['Atendidas'] = mysql_result($results, $i,'qtd');
				}
				else if (mysql_result($results, $i,'Atendida') == 'N'){
					$qtdReclamacoes['Não Atendidas'] = mysql_result($results, $i,'qtd');	
				}	
		    }
			
			return $qtdReclamacoes;
		}
		
		public function getProblemasRelatados($CNAESetor, $UF){
			$queryReclamacoes = "SELECT DescricaoProblema, COUNT(*) AS qtd FROM Reclamacao WHERE 1=1 ";
			
			if($CNAESetor != null){
				$queryReclamacoes .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryReclamacoes .= " AND UF = '" . $UF . "' ";
			}
			
			$queryReclamacoes .= " GROUP BY DescricaoProblema ORDER BY COUNT(*) DESC LIMIT 5";
			
			$results = mysql_query($queryReclamacoes);
			$qtdReclamacoes = array();
			
			for($i = 0; $i< mysql_num_rows($results); $i++){
				$qtdReclamacoes[utf8_decode(mysql_result($results, $i,'DescricaoProblema'))] = 0;
			}
			
			$results = mysql_query($queryReclamacoes);
			
			for($i = 0; $i< mysql_num_rows($results); $i++){
				$qtdReclamacoes[utf8_decode(mysql_result($results, $i,'DescricaoProblema'))] += mysql_result($results, $i,'qtd');
		    }
			
			return $qtdReclamacoes;
		}
   }

?>