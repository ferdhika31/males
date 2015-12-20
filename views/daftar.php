<?php
	// $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
	<body class="">
		<div id="root" class="page">
			<header class="Header">
				<div class="container">
					<?php $this->output('menu');?>

					<div class="Banner">
						<h1 class="Banner__heading utility-center">
							Daftar Materi
						</h1>
					</div>

				</div>
			</header>

			<div class="container">
				<section id="pjax-container" class="Grid__row">
					<div id="main" class="Filterable">
						<div class="sidebar">
							<h3 class="utility-heading-top">Saring</h3>

							<ul class="List--Naked">
							<?php if(!empty($semester)):?>
								<li class="Filterable__item">
									<h4 class="Filterable__heading">Semester</h4>

								<?php 
									foreach ($semester as $smt):
								?>
									<label class="Filterable__label">
										<input name="semester" value="<?php echo $smt->semester_id;?>" type="radio">
										<a href=""><?php echo $smt->semester;?></a>
									</label><br>
								<?php
									endforeach;
								?>

								</li>
							<?php endif;?>
								<li class="Filterable__item">
									<h4 class="Filterable__heading">T/P</h4>

									<label class="Filterable__label">
										<input name="tp" type="radio">
										<a href="">Teori</a>
									</label><br>

									<label class="Filterable__label">
										<input name="tp" type="radio">
										<a href="">Praktek</a>
									</label><br>
								</li>

								<li class="Filterable__item">
									<h4 class="Filterable__heading">Mata Kuliah</h4>

									<label class="Filterable__label">
										<input name="lesson_type" type="radio">
										<a href="">Single Lesson</a>
									</label><br>

									<label class="Filterable__label">
										<input name="lesson_type" type="radio">
										<a href="">Series Episode</a>
									</label>
								</li>

								<li class="Filterable__item">
									<a href="/lessons" class="Button Button--Block">Saring</a>
								</li>
							</ul>
						</div>

						<div class="primary">
							<ul class="Lesson-List ">
								<li class="Lesson-List__item">
									<span class="Lesson-List__title utility-flex">
										<a href="">
											<strong>Dasar Dasar Pemrograman:</strong>
										</a>

										<a href="">
											Pengenalan Algoritma Dasar
										</a>
									</span>

									<span class="Lesson-List__date">
										<strong>Minggu 1</strong> | Rabu, 09 Desember 2015
									</span>
								</li>

								<li class="Lesson-List__item">
									<span class="Lesson-List__title utility-flex">
										<a href="">
											<strong>Dasar Dasar Pemrograman:</strong>
										</a>

										<a href="">
											Pengenalan Algoritma Dasar Part 2
										</a>
									</span>

									<span class="Lesson-List__date">
										<strong>Minggu 2</strong> | Rabu, 09 Desember 2015 
									</span>
								</li>
							</ul>
						</div>
					</div>
					<ul class="pagination"><li class="disabled"><span>«</span></li> <li class="active"><span>1</span></li><li><a href="https://laracasts.com/lessons/?page=2">2</a></li><li><a href="https://laracasts.com/lessons/?page=3">3</a></li><li><a href="https://laracasts.com/lessons/?page=4">4</a></li><li><a href="https://laracasts.com/lessons/?page=5">5</a></li><li><a href="https://laracasts.com/lessons/?page=6">6</a></li><li><a href="https://laracasts.com/lessons/?page=7">7</a></li><li><a href="https://laracasts.com/lessons/?page=8">8</a></li><li class="disabled"><span>...</span></li><li><a href="https://laracasts.com/lessons/?page=12">12</a></li><li><a href="https://laracasts.com/lessons/?page=13">13</a></li> <li><a href="https://laracasts.com/lessons/?page=2" rel="next">»</a></li></ul>
				</section>
			</div>
		</div>