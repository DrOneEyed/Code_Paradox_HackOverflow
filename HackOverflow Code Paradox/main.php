<!DOCTYPE HTML>
<html>
	<head>
		<title>NGO Guard</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
		
		<style>
			meter{
				margin: 0 auto 1.2em;
				display: block;
				width: 300px;
				height: 69px;
			}
			meter::-webkit-meter-bar {
				background: none;
				background-color: lightgrey;
				box-shadow: 0 3px 3px -3px #333 inset;
			}
			meter::-webkit-meter-optimum-value {
				box-shadow: 0 3px 3px -3px #999 inset;
				background-image: linear-gradient( 90deg, #262f3d 5%, #262f3d 5%, #262f3d 15%, #262f3d 15%, #262f3d 55%, #262f3d 55%, #262f3d 95%, #262f3d 95%, #262f3d 100%);
				background-size: 100% 100%;
			}
			.center {
				display: flex;
				justify-content: center;
				align-items: center;
				height: 69px;
			}
			.center1{
				justify-content: center;
				align-items: center;
				height: auto;
			}
		</style>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="">
							<span class="icon"><img src="images/shield-2-64.ico" alt="" /></span>
						</div>
						<div class="content">
							<div class="inner">
								<h1>NGO Guard</h1>
								<p>Your donations, secured.</p>
							</div>
						</div>
						<nav>
							<ul>
								<li><a href="#team">Team</a></li>
								<li><a href="#NGO">NGO Guard</a></li>
								<li><a href="#about">About Us</a></li>
								<li><a href="#contact">Feedback</a></li>
								<!--<li><a href="#elements">Elements</a></li>-->
							</ul>
						</nav>
					</header>

				<!-- Main -->
					<div id="main">

						<!-- Intro -->
							<article id="team">
								<h2 class="major">Meet The Team</h2>
								<span class="image main"><img src="images/bt_baz_x2.jpg" alt="" /></span>
								<p>We are a team of two. Vinayak Verma and Divyaansh Agarwal, trying to persue simple social issues with our amateur coding knowledge. Just two friends at work.</p>
								<p>You can contact us via our respective emails:</p>
								<ul>
									<li><a href="mailto:vinayakverma2002@gmail.com">Vinayak Verma</a></li>
									<li><a href="mailto:divyaansh.agarwal.19@gmail.com">Divyaansh Agarwal</a></li>
								</ul>
							</article>

						<!-- NGO Guard -->
							<article id="NGO">
								<h2 class="major">NGO Guard</h2>
								<label>Enter NGO ID:</label>
								<form name="form" action="#det" method="post">
									<input type="text" id="ngo_id" name="ngo_id" required>
									<br>
									<button>Check Now</button>
								</form>
							</article>
							
							<article id="det">
								<?php
									if ($_SERVER['REQUEST_METHOD']=='POST')
									{
										$id = $_POST['ngo_id'];
										$conn = mysqli_connect("localhost", "root", "", "ngo guard");
										if($conn===false)
										{
											die("ERROR: Could Not Connect To DB" . mysqli_connect_error());
										}
										$sql1= "SELECT * FROM ngo_details WHERE NGO_ID = '$id'";
										$result = mysqli_query($conn, $sql1);
										$check = mysqli_fetch_array($result);
									}
								?>
								<h2 class="major">NGO Details</h2>
								<table >
									<tr>
										<th>Name</th>
										<td><?php echo $check['NGO_NAME'] ?></td>
									</tr>
									<tr>
										<th>NGO ID</th>
										<td><?php echo $check['NGO_ID'] ?></td>
									</tr>
									<tr>
										<th>Deals In</th>
										<td><?php echo $check['DEALS_IN'] ?></td>
									</tr>
                                    <tr>
                                        <th>Phone Number</th>
                                        <td><?php echo $check['PHONE_NO'] ?></td>
                                    </tr>
									<tr>
										<th>Address</th>
										<td><?php echo $check['ADDRESS'] ?></td>
									</tr>
								</table>
								<button onclick="location.href='#meter'">Check NGO-Guard Rating</button>
							</article>

							<article id="meter">
								<h2 class="major" style="display: inline-block">Insights</h2><h5 style="display: inline-block">(2020-Current)</h5>
								<br>
								<h3>Total Funding Secured: &#8377;
									<?php
										$overseas= "SELECT sum(AMOUNT) FROM txn_details WHERE NGO_ID = '$id' and LOCATION = 'OVERSEAS'";
										$result = mysqli_query($conn, $overseas);
										$ost = mysqli_fetch_array($result);

										$indiaP= "SELECT sum(AMOUNT) FROM txn_details WHERE NGO_ID = '$id' and LOCATION = 'INDIA PUBLIC'";
										$result = mysqli_query($conn, $indiaP);
										$pt = mysqli_fetch_array($result);

										$indiaG= "SELECT sum(AMOUNT) FROM txn_details WHERE NGO_ID = '$id' and LOCATION = 'INDIA GOVT'";
										$result = mysqli_query($conn, $indiaG);
										$gt = mysqli_fetch_array($result);
										
										$tot = $ost['sum(AMOUNT)'] + $pt['sum(AMOUNT)'] + $gt['sum(AMOUNT)'];
										echo $tot;
										
										$data = "SELECT DATE_OF_TXN, LOCATION, AMOUNT FROM txn_details WHERE NGO_ID = '$id' ORDER BY DATE_OF_TXN";
										$result = mysqli_query($conn, $data);

										$inTot = $pt['sum(AMOUNT)'] + $gt['sum(AMOUNT)'];

										$inPer = ($inTot/$tot) * 100;
										$govPer = $gt['sum(AMOUNT)']/$inTot * 100;
										$points = 0;

										if($inPer > 70){$points+=3;}
										else if($inPer > 50){$points+=2;}
										else if($inPer > 30){$points+=1;}

										if($govPer > 70){$points+=2;}
										else if($govPer > 50){$points+=1;}
										
										$per = ($points/5) * 100;
										if($per < 10){$per = 10;}

										$dataP = "SELECT DATE_OF_TXN, AMOUNT FROM `txn_details` WHERE NGO_ID = '$id'";
										$result1 = mysqli_query($conn, $dataP);
										$dp = array();
										foreach($result1 as $row){
											$dp[] = $row;
										}
									?>
								</h3>

								<canvas id="donut" style="width:100%;max-width:700px"></canvas>
								
								<script>
									var xValues = ["India Public", "India Govt.", "Overseas"];
									var yValues = [ <?php echo $pt['sum(AMOUNT)']?>,  <?php echo $gt['sum(AMOUNT)']?>, <?php echo $ost['sum(AMOUNT)']?>];
									var barColors = ["#393b46", "#262f3d", "#5e646f"];

									new Chart("donut", {
										type: "doughnut",
										data: {
											labels: xValues,
											datasets: [{
											backgroundColor: barColors,
											data: yValues
											}]
										},
										options: {
											title: {
											display: true,
											text: "Fund Distribution"
											}
										}
									});
									Chart.defaults.global.defaultFontColor = "#fff"
								</script>
								<br>
								<br>
								<h3>Transaction Details</h3>
								<table>
									<thead>
										<th>Date Of Transaction</th>
										<th>Location</th>
										<th>Amount (&#8377;)</th>
									</thead>
									<tbody>
										<?php
											while($row = mysqli_fetch_array($result)){
												echo "<tr>";
													echo "<td>" . $row['DATE_OF_TXN'] . "</td>";
													echo "<td>" . $row['LOCATION'] . "</td>";
													echo "<td>" . $row['AMOUNT'] . "</td>";
												echo "</tr>";
											}
										?>
									</tbody>
								</table>
								<br>
								<br>
								<h3>Donation Trends</h3>
								<div class = "center1">
									<canvas id="line" style="width:100%;max-width:600px"></canvas>
								</div>

								<script>
									
									var xarr = new Array();
									var yarr = new Array();
									var vals = <?php echo json_encode($dp); ?>;
									for(let i = 0; i < vals.length; i++){
										xarr[i] = vals[i].DATE_OF_TXN;
										yarr[i] = vals[i].AMOUNT;
									}
									new Chart("line", {
									type: "line",
									data: {
										labels: xarr,
										datasets: [{
											fill: false,
											lineTension: 0.36,
											backgroundColor: "rgba(0,0,255,1.0)",
											borderColor: "#5e646f",
											pointBackgroundColor: "#FFF",
											data: yarr
										}]
									},
									options: {
										legend: {display: false}
									}
									});
								</script>
								<br>
								<br>

								<h2 class="major">NGO-Guard Rating</h2>
								
								<meter min = "0" max = "100" value="<?php echo $per?>" 
								low="33" high="66" optimum="80"></meter>
								<div class="center">
									<button>NGO Guard Has Rated This NGO: <?php echo $per?>&#37;</button>
								</div>
								<br>
							</article>

						<!-- About -->
							<article id="about">
								<h2 class="major">About Us</h2>
								<p>We are a team which is passionate about resolving social issues through our programming endeavours. Our newest project takes in account for monitoring all the fradulent NGOs used for money laundering, utilising funds donated for a social cause. Our project, NGOGuard utilises various data points to rate various NGOs for legitimacy.</p>
							</article>

						<!-- Contact -->
							<article id="contact">
								<h2 class="major">Help Us Improve</h2>
								<form method="post" action="mailto:profitorigin8@gmail.com">
									<div class="fields">
										<div class="field half">
											<label for="name">Name</label>
											<input type="text" name="name" id="name" />
										</div>
										<div class="field half">
											<label for="email">Email</label>
											<input type="text" name="email" id="email" />
										</div>
										<div class="field">
											<label for="message">Message</label>
											<textarea name="message" id="message" rows="4"></textarea>
										</div>
									</div>
									<ul class="actions">
										<li><input type="submit" value="Send Message" class="primary" /></li>
										<li><input type="reset" value="Reset" /></li>
									</ul>
								</form>
								<ul class="icons">
									<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>
								</ul>
							</article>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; HackOverflow. Team: Code: Paradox</a>.</p>
					</footer>

			</div>

		<!-- BG -->
			<div id="bg"></div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
