<html>
<head>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php
   include("database.php");
   
   $database = new Database();
   $database->connectDB("localhost","procon","root","");
   
   $CNAEPrincipal = null;
   $UF = null;
   
   if(isset($_GET['CNAE'])){
		$CNAEPrincipal = $_GET['CNAE'];
   }
   
   if(isset($_GET['UF'])){
		$UF = $_GET['UF'];
   }
   
   $resultsReclamacoes = $database->getRankingReclamacoes($CNAEPrincipal, $UF);
   $resultsNaoAtendidas = $database->getRankingNaoAtendidas($CNAEPrincipal, $UF);
   $resultsAtendidasNaoAtendidas = $database->getQtdAtendidasNaoAtendidas($CNAEPrincipal, $UF);
   $resultsProblemasRelatados = $database->getProblemasRelatados($CNAEPrincipal, $UF);
   
   //montando os arrays javascript:
   $arrayReclamacoes = "";
   $primeiro = true;
   foreach($resultsReclamacoes as $reclamacao){
		if(!$primeiro){
			$arrayReclamacoes .= ",";
		}
		else{
			$primeiro = false;
		}
		
		$arrayReclamacoes .= "['".$reclamacao['NomeEmpresa'] ."', ". $reclamacao['qtd'] . "]";
   }
   
   $arrayNaoAtendidas = "";
   $primeiro = true;
   foreach($resultsNaoAtendidas as $reclamacao){
		if(!$primeiro){
			$arrayNaoAtendidas .= ",";
		}
		else{
			$primeiro = false;
		}
		
		$arrayNaoAtendidas .= "['".$reclamacao['NomeEmpresa'] ."', ". $reclamacao['qtd'] . "]";
   }
   
   $arrayAtendidasNaoAtendidas = "";
   $primeiro = true;
   
   foreach($resultsAtendidasNaoAtendidas as $label => $value){
		if(!$primeiro){
			$arrayAtendidasNaoAtendidas .= ",";
		}
		else{
			$primeiro = false;
		}
		
		$arrayAtendidasNaoAtendidas .= "['".$label."'," . $value ."]";
   }
   
   $arrayProblemasRelatados = "";
   $primeiro = true;
   
   foreach($resultsProblemasRelatados as $label => $value){
		if(!$primeiro){
			$arrayProblemasRelatados .= ",";
		}
		else{
			$primeiro = false;
		}
		
		$arrayProblemasRelatados .= "['".$label."'," . $value ."]";
   }
  
  //arrays de estado e área:
  
  $estados = array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");
  $setores = array("6422100"=>"Financeiro","6120501"=>"Telefonia","2751100"=>"Eletrodomésticos","4713001"=>"Comércio","3514000"=>"Energia","2621300"=>"Informática","5111100"=>"Companhias Aéreas","3600601"=>"Saneamento","4511101"=>"Concessionárias","6613400"=>"Cartões","8531700"=>"Educação Superior","6550200"=>"Planos de Saúde");
?>

</head>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
	<script src="http://d3js.org/d3.v3.min.js"></script>
	<div id="contentRight">			
			<!-- GRÁFICOS -->
		<div>	
			<div style="float: left; width: 600; padding-left:10px; ">
				<p style="font-family: 'Tahoma'; font-size:14pt"><? if($UF != null){?><b>Estado</b>: <? echo $estados[$UF]; } else{ echo "Brasil";} ?></p>
				<p>
					<div style="float:left; font-family: 'Tahoma'; font-size:14pt"><? if($CNAEPrincipal != null){?><b>Setor</b>: <? echo $setores[$CNAEPrincipal]; } else{ echo "Todos os Setores";} ?></div>
					<div style="float:right; font-family: 'Tahoma'; font-size:12pt"><a href="javascript:parent.UF='';parent.CNAE='';location.href='graficos.php'">Limpar os Filtros</a></div>
				</p>
			</div>
			<br />
			
			<div style="float: left; width: 600; padding-top:20px" id="barChart1">
				<script type="text/javascript">
				<? if(strlen($arrayReclamacoes) > 0) { ?>
				  google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Empresa', 'Quantidade de Reclamações'],
					  <?=$arrayReclamacoes?>
					]);

					var options = {
					  title: 'Empresas que mais recebem reclamações',
					  titleTextStyle: {fontName: 'Tahoma', fontSize:17},
					  width: 600,
					  colors: ['#549b60'],
					  legend:'none'
					};

					var chart = new google.visualization.BarChart(document.getElementById('barChart1'));
					chart.draw(data, options);
				  }
				  <?}?>
				</script>
			</div>

			<br />
	
			<div style="float: left; width: 600;" id="barChart2">
				<script type="text/javascript">
				  <? if(strlen($arrayNaoAtendidas) > 0) { ?>
				  google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Empresa', 'Quantidade de Reclamações'],
					  <?=$arrayNaoAtendidas?>
					]);

					var options = {
					  title: 'Empresas que menos atendem reclamações',
					  titleTextStyle: {fontName: 'Tahoma', fontSize:17},
					  width: 600,
					  colors: ['#549b60'],
					  legend: 'none'
					};

					var chart = new google.visualization.BarChart(document.getElementById('barChart2'));
					chart.draw(data, options);
				  }
				  <?}?>
				</script>
			</div>
			
			<br />
			<div style="float: left; width: 600;" id="pieChart1">
					<script type="text/javascript">
					<? if(strlen($arrayAtendidasNaoAtendidas) > 0) { ?>
					  google.load("visualization", "1", {packages:["corechart"]});
					  google.setOnLoadCallback(drawChart);
					  function drawChart() {
						var data = google.visualization.arrayToDataTable([
						  ['Status', 'Quantidade de Reclamações'],
						  <?=$arrayAtendidasNaoAtendidas?>
						]);

						var options = {
						  title: 'Reclamações Atendidas x Não Atendidas',
						  titleTextStyle: {fontName: 'Tahoma', fontSize:17},	
						  width: 600,
						  height:250
						};

						var chart = new google.visualization.PieChart(document.getElementById('pieChart1'));
						chart.draw(data, options);
					  }
					  <?}?>
					</script>
			</div>
			
			<div style="float: left; width: 600;" id="pieChart2">
					<? if(strlen($arrayProblemasRelatados) > 0) { ?>
					<script type="text/javascript">
					  google.load("visualization", "1", {packages:["corechart"]});
					  google.setOnLoadCallback(drawChart);
					  function drawChart() {
						var data = google.visualization.arrayToDataTable([
						  ['Problema', 'Quantidade de Reclamações'],
						  <?=$arrayProblemasRelatados?>
						]);

						var options = {
						  title: 'Problemas Mais Relatados',
						  titleTextStyle: {fontName: 'Tahoma', fontSize:17},	
						  width: 600,
						  height:250
						};

						var chart = new google.visualization.PieChart(document.getElementById('pieChart2'));
						chart.draw(data, options);
					  }
					  <?}?>
					</script>
			</div>
		</div>

</body>

</html>