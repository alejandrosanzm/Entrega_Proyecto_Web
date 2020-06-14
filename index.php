<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Palabras Por Sonrisas</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png" />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

	<!-- CSS Files -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/material-bootstrap-wizard.css" rel="stylesheet" />

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="assets/css/demo.css" rel="stylesheet" />
</head>

<body>
	<!-- style="background-image: url('assets/img/wizard-book.jpg')" -->
	<div class="set-full-height" style="background-color:white;">
		<!--  Made With Material Kit  -->
		<a href="" class="made-with-mk">
			<div class="brand">Pp<i class="fa fa-smile-o" aria-hidden="true"></i></div>
			<div class="made-with">Proyecto <strong>Salud Madrid</strong></div>
		</a>

	    <!--   Big container   -->
	    <div class="container">
	        <div class="row">
		        <div class="col-sm-8 col-sm-offset-2">
		            <!--      Wizard container        -->
		            <div class="wizard-container">
		                <div class="card wizard-card" data-color="red" id="wizard">
		                    <form action="send_letter.php" method="post" enctype="multipart/form-data">

  <input type="hidden" name="submit" value="true">
		                    	<div class="wizard-header">
		                        	<h3 class="wizard-title">
		                        		Palabras Por Sonrisas
		                        	</h3>
									<h5>Escribe una carta, y haz que tus palabra lleguen a todos.</h5>
		                    	</div>
								<div class="wizard-navigation">
									<ul>
			                            <li><a href="#details" data-toggle="tab">Bienvenido</a></li>
			                            <li><a href="#captain" data-toggle="tab">Primero unos detalles</a></li>
			                            <li><a href="#description" data-toggle="tab">Escribe tu carta</a></li>
																	<li><a href="#finalpane" data-toggle="tab">Envíanosla</a></li>
			                        </ul>
								</div>

		                        <div class="tab-content">
		                            <div class="tab-pane" id="details">
		                            	<div class="row">
			                            	<div class="col-sm-12">
			                                	<h4 class="info-text"> ¡Hola!, ¿Cómo te llamas?</h4>
			                            	</div>
		                                	<div class="col-sm-12">
												<div class="input-group" style="margin:auto;margin-top:5%;max-width:60%;">
													<span class="input-group-addon">
														<i class="fa fa-question" style="color:#f44336;"></i>
													</span>
													<div class="form-group label-floating">

			                                          	<label class="control-label">Tu Nombre:</label>
																									<?php
																									$empty = "";
																									$arrayNames = array("Zipi" , "Zape", "Capitán Trueno", "Mortadelo", "Filemón", "Super López", "Rompetechos", "Jabato" );
																												echo '<input name="name" id="nameinput"type="text" placeholder="                      Ej. '.$arrayNames[rand(0,(sizeof($arrayNames)-1) )].'" class="form-control" onfocus="this.placeholder=\'\'" required>';
																									 ?>
			                                          	<!-- <input name="name" id="nameinput"type="text" placeholder="                            nya" class="form-control" onfocus="this.placeholder=''" required> -->
			                                        </div>
												</div>
		                                	</div>

		                            	</div>
		                            </div>
		                            <div class="tab-pane" id="captain">
		                                <h4 class="info-text">Necesitamos saber a quién va dirigida tu carta. </h4>
		                                <div class="row">
		                                    <div class="col-sm-10 col-sm-offset-1">

																					  <?php require 'profiles.php'; ?>

		                                    </div>
		                                </div>
		                            </div>
		                            <div class="tab-pane" id="description">
		                                <div class="row">
		                                    <h4 class="info-text"> ¡Finalmente ya puedes escribir tu carta!</h4>
		                                    <div class="col-sm-6 col-sm-offset-1">
	                                    		<div class="form-group">
		                                            <label>Tu Carta</label>
		                                            <textarea class="form-control" name="letter" placeholder="" rows="6"></textarea>
																								</div>

																								  <div class="custom-file">
																								    <input type="file" class="custom-file-input" id="customFile" name="image">
																								    <label class="custom-file-label" for="customFile">Sube tu imagen, dibujo...</label>



																								<script>
																								// Add the following code if you want the name of the file appear on select
																								$(".custom-file-input").on("change", function() {
																								  var fileName = $(this).val().split("\\").pop();
																								  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
																								});
																								</script>


		                                        </div>
		                                    </div>
		                                    <div class="col-sm-4">
		                                    	<div class="form-group">
		                                            <label class="control-label">Ejemplo de consejos</label>
		                                            <p class="description">"Imagino que los días se te hacen largos en el hospital, así que he decidido escribirte para que este día se te haga un poco más corto.
																									Hasta los momentos peores tienen una pequeña luz, espero que tú puedas encontrarla. Te mando mucho animo"</p>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
																<div class="tab-pane" id="finalpane">
																		<div class="row">
																				<h4 class="info-text"><i class="material-icons">question_answer</i> ¡Gracias!</h4>
																				<div class="col-sm-6 col-sm-offset-1">
																					<div class="form-group">
																						<label for="hospitals">Elige los hospitales de destino:</label><br><br>
																						<div class="boxes">

																									  <?php require 'hospitals.php'; ?>
																							<!-- Para seleccionar atributo checked
																							<input type="checkbox" id="box-1" checked>
																							<label for="box-1">Sustainable typewriter cronut</label>

																							<input type="checkbox" id="box-2" checked>
																							<label for="box-2">Gentrify pickled kale chips </label>

																							<input type="checkbox" id="box-3" checked>
																							<label for="box-3">Gastropub butcher</label>-->
																						</div>
																						</div>
																				</div>
																				<div class="col-sm-4">
																					<div class="form-group">
																								<label>Elige si todos podrán leer tu carta:</label><br><br>
																								<div class="boxes">
																									<input type="checkbox" name="public" id="box-4">
																									<label for="box-4">Quiero que mi carta sea pública</label>
																								</div>
																						</div>
																				</div>
																		</div>
																</div>
		                        </div>
	                        	<div class="wizard-footer">
	                            	<div class="pull-right">
	                                    <input type='button' class='btn btn-next btn-fill btn-danger btn-wd' name='next' value='Siguiente' />
	                                    <input type='submit' class='btn btn-finish btn-fill btn-danger btn-wd' name='finish' value='Enviar' />
	                                </div>
	                                <div class="pull-left">
	                                    <input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Anterior' />

																		</form>

										<div class="footer-checkbox">
											<div class="col-sm-12">
											  <div class="checkbox">
												  <label>
													  <!-- <input type="checkbox" name="optionsCheckboxes"> -->
														<i class="fa fa-eye" style="color:#450b63;"></i>
												  </label>
												  <a href="publicList.php">Ver las cartas públicas</a>
											  </div>
										  </div>
										</div>
	                                </div>
	                                <div class="clearfix"></div>
	                        	</div>
		                    </form>
		                </div>
		            </div> <!-- wizard container -->
		        </div>
	    	</div> <!-- row -->
		</div> <!--  big container -->

	    <div class="footer">
	        <div class="container text-center" style="color:#1c2322;">
	             Footer genérico <i class="fa fa-medkit" aria-hidden="true"></i>. Comunidad de Madrid.
	        </div>
	    </div>
	</div>

</body>
	<!--   Core JS Files   -->
	<script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery.bootstrap.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="assets/js/material-bootstrap-wizard.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
</html>
