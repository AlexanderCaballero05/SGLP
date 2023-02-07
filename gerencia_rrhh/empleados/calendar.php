<?php

if (!isset($_GET["action"])) {


	if ($action == "vacaciones") {

		/////////////////////////////////////////////////////////////
		///////////// INFO CALENDAR VACACIONES //////////////////////


		$c_historico_now = mysqli_query($conn, "SELECT * FROM rr_hh_vacaciones_tomadas WHERE  estado = 'A' AND identidad = '$identidad'   ORDER BY fecha ASC ");

		$mockData = [];
		while ($reg_historico = mysqli_fetch_array($c_historico_now)) {

			$fecha = $reg_historico['fecha']."T00:00:00 Z";

			$desc = ["time" => $fecha, "cls" => "bg-green-alt", "desc" => "Vacaciones"];
			array_push($mockData, $desc);
		}


		$mockDataJSON = json_encode($mockData);


		///////////// INFO CALENDAR VACACIONES //////////////////////
		/////////////////////////////////////////////////////////////



	}

} else {

	require '../../conexion.php';


	if ($_GET["action"] == "vacaciones") {

		//////////////////////////////////////////////////
		///////////// INFO CALENDAR //////////////////////

		$identidad = $_GET['identidad'];

		$c_historico_now = mysqli_query($conn, "SELECT * FROM rr_hh_vacaciones_tomadas WHERE  estado = 'A' AND identidad = '$identidad'   ORDER BY fecha ASC ");

		$mockData = [];
		while ($reg_historico = mysqli_fetch_array($c_historico_now)) {
			$fecha = $reg_historico['fecha']."T00:00:00 Z";

			$desc = ["time" => $fecha, "cls" => "bg-green-alt", "desc" => "Vacaciones"];
			array_push($mockData, $desc);
		}


		$mockDataJSON = json_encode($mockData);

		///////////// INFO CALENDAR //////////////////////
		//////////////////////////////////////////////////

	}
}

?>


<!-- ///////////////////////////////////////////////////// -->
<!-- ////////////////////// CALENDAR ///////////////////// -->

<link rel="stylesheet" href="../../assets/calendar/css/theme.css">
<link rel="stylesheet" href="../../assets/calendar/css/spinner.css">
<link rel="stylesheet" href="../../assets/calendar/css/style.css">
<style>
	/*
*
* ==========================================
* CUSTOM UTIL CLASSES
* ==========================================
*
*/
.clearfix::after,
.calendar ol::after {
  content: ".";
  display: block;
  height: 0;
  clear: both;
  visibility: hidden;
}

/* ================
Calendar Styling */
.calendar {
  min-width: 375px;
  position: relative;
}
.calendar a{
  cursor: pointer;
}
.calendar.noselect {
    -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
    -khtml-user-select: none; /* Konqueror HTML */
    -moz-user-select: none; /* Old versions of Firefox */
    -ms-user-select: none; /* Internet Explorer/Edge */
    user-select: none; /* Non-prefixed version, currently
    supported by Chrome, Edge, Opera and Firefox */
}
.month-year-btn {
  color: #444;
}
.month-year {
  width: 11rem;
}
.month, .year {
  font-size: 1.5rem;
}

.initials {
}
@media (max-width: 550px) {
  .initials {
    position: relative;
    display: inline-block;
    overflow: hidden;
    white-space: nowrap;
    letter-spacing: 1ch; 
    width: 1.9ch;
  }
}

.calendar ol li {
  float: left;
  width: 14.28571%;
}

.calendar .day-names {
  border-bottom: 1px solid #eee;
  color: #444;
}

.calendar .days { 
  border-bottom: 1px solid #eee;
}
.calendar .days li {
  min-height: 6rem;
  cursor: pointer;
}

.calendar .days li .date {
  margin-bottom: 0.5rem;
}

.calendar .days li .event {
  font-size: 1rem;
  font-weight: bold;
  padding: 0.4rem 0.6rem 0.4rem  0.8rem;
  color: white;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  border-radius: 4rem;
  margin: 0 1px 1px 0;
}

.calendar .days li .event.span-2 {
  width: 200%;
}

.calendar .days li .event.begin {
  border-radius: 1rem 0 0 1rem;
}

.calendar .days li .event.end {
  border-radius: 0 1rem 1rem 0;
}

.calendar .days li .event.clear {
  background: none;
}

.calendar .days li:nth-child(n+29) {
  border-bottom: none;
}

.calendar .days li.outside .date  {
  color: #ddd;
}
.calendar .days li.today .date  {
  text-decoration: underline;
}
</style>

<br>

<div class="card" style='margin-left:5px; margin-right:5px'>
	<div class="card-body">
		<div id="calendar" class="bg-white"></div>
	</div>
</div>

<script src="../../assets/calendar/js/moment.min.js"></script>
<script type="text/javascript">


	function loadCalendar(dataCalendar) {


		const Spinner = (id) => ({
			id: id,
			el: null,
			renderSpinner() {
				const frgSpinner = document.createRange().createContextualFragment(`
        <div class="spinner d-flex justify-content-center align-items-center">
            <div class="spinner-grow text-light" style="width: 4rem; height: 4rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        `);
				this.el = document.getElementById(this.id);
				this.el.innerHTML = ''; //replacing
				this.el.appendChild(frgSpinner);
				return this;
			},
			async delay(delay = 2000) {
				await new Promise(resolve => setTimeout(resolve, delay));
			}
		});


		const StopEventPropagation = (e) => {
			if (!e) return;
			e.cancelBubble = true;
			if (e.stopPropagation) e.stopPropagation();
		};

		const Calendar = (id) => ({
			id: id,
			data: [],
			el: undefined,
			y: undefined,
			m: undefined,
			onDateClick(e) {
				StopEventPropagation(e);
				const el = e.srcElement;
				alert(el.textContent);
				console.log('click');
				console.log(el);
			},
			onEventClick(e) {
				StopEventPropagation(e);
				const el = e.srcElement;
				alert(el.textContent);
				console.log('click');
				console.log(el);
			},
			bindData(events) {
				this.data = events.sort((a, b) => {
					if (a.time < b.time) return -1;
					if (a.time > b.time) return 1;
					return 0;
				});
			},
			renderEvents() {
				if (!this.data || this.data.length <= 0) return;
				const lis = this.el.querySelectorAll(`.${this.id} .days .inside`);
				let y = this.el.querySelector('.month-year .year').innerText;
				let m = lis[0].querySelector('.date').getAttribute('month');
				lis.forEach((li) => {
					try {
						let d = li.innerText;
						let divEvents = li.querySelector('.events');
						li.onclick = this.onDateClick;
						this.data.forEach((ev) => {
							try {
								let evTime = moment(ev.time);
								if (evTime.year() == y && evTime.month() == m && evTime.date() == d) {
//									let frgEvent = document.createRange().createContextualFragment(`
//                                <div time="${ev.time}" class="event ${ev.cls}">${evTime.format('h:mma')} ${ev.desc}</div>
//                            `);
									let frgEvent = document.createRange().createContextualFragment(`
                                	<div time="${ev.time}" class="event ${ev.cls}"  > ${ev.desc}  </div>
                            		`);

									divEvents.appendChild(frgEvent);
									let divEvent = divEvents.querySelector(`.event[time='${ev.time}']`);
									divEvent.onclick = this.onEventClick;

								}
							} catch (err2) {
								console.log(err2);
							}
						});
					} catch (err1) {
						console.log(err1);
					}
				});
			},
			render(y, m) {
				//-------------------------------------------------------------------------------------------
				//first time when you call render() without params, it is going to default to current date.
				//this logic here is to make sure if you re-render by calling render() without any param again,
				//if the calendar is already looking at some other month, then it will get the updated data, but
				//the calendar will not jump back to current month and stay at the previous month you are looking at.
				//this is useful when server side has updated events, calendar can re-bindData() and re-render() 
				//itself correctly to reflect any changes.
				try {
					if (isNaN(y) && isNaN(this.y)) {
						this.y = moment().year();
					} else if ((!isNaN(y) && isNaN(this.y)) || (!isNaN(y) && !isNaN(this.y))) {
						this.y = y > 1600 ? y : moment().year(); //calendar doesn't exist before 1600! :)
					}
					if (isNaN(m) && isNaN(this.m)) {
						this.m = moment().month();
					} else if ((!isNaN(m) && isNaN(this.m)) || (!isNaN(m) && !isNaN(this.m))) {
						this.m = m >= 0 ? m : moment().month(); //momentjs month starts from 0-11
					}
					//------------------------------------------------------------------------------------------

					const d = moment().year(this.y).month(this.m).date(1); //first date of month
					const now = moment();
					const frgCal = document.createRange().createContextualFragment(`
            <div class="calendar noselect p-5">
                <div class="month-year-btn d-flex justify-content-center align-items-center mb-2">
                    <a class="prev-month"><i class="fas fa-caret-left fa-lg m-3"></i></a>
                    <div class="month-year d-flex justify-content-center align-items-center">
                        <div class="month mb-2 mr-2">${moment().month(this.m).format('MMMM')}</div>
                        <div class="year mb-2">${this.y}</div>
                    </div>
                    <a class="next-month"><i class="fas fa-caret-right fa-lg m-3" aria-hidden="true"></i></a>
                </div>
                <ol class="day-names list-unstyled">
                    <li><h6 class="initials">Dom</h6></li>
                    <li><h6 class="initials">Lun</h6></li>
                    <li><h6 class="initials">Mar</h6></li>
                    <li><h6 class="initials">Mie</h6></li>
                    <li><h6 class="initials">Jue</h6></li>
                    <li><h6 class="initials">Vie</h6></li>
                    <li><h6 class="initials">Sab</h6></li>
                </ol>
            </div>
            `);
					const isSameDate = (d1, d2) => d1.format('YYYY-MM-DD') == d2.format('YYYY-MM-DD');
					let frgWeek;
					d.day(-1); //move date to the oldest Sunday, so that it lines up with the calendar layout
					for (let i = 0; i < 6; i++) { //loop thru 35 boxes on the calendar month
						frgWeek = document.createRange().createContextualFragment(`
                <ol class="days list-unstyled" week="${d.week()}">
                    <li class="${d.add(1,'d'),this.m != d.month()?' outside':'inside'}${isSameDate(d,now)?' today':''}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                    <li class="${d.add(1,'d'),this.m != d.month()?' outside':'inside'}${isSameDate(d,now)?' today':''}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                    <li class="${d.add(1,'d'),this.m != d.month()?' outside':'inside'}${isSameDate(d,now)?' today':''}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                    <li class="${d.add(1,'d'),this.m != d.month()?' outside':'inside'}${isSameDate(d,now)?' today':''}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                    <li class="${d.add(1,'d'),this.m != d.month()?' outside':'inside'}${isSameDate(d,now)?' today':''}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                    <li class="${d.add(1,'d'),this.m != d.month()?' outside':'inside'}${isSameDate(d,now)?' today':''}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                    <li class="${d.add(1,'d'),this.m != d.month()?' outside':'inside'}${isSameDate(d,now)?' today':''}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                </ol>
                `);
						frgCal.querySelector('.calendar').appendChild(frgWeek);
					}

					frgCal.querySelector('.prev-month').onclick = () => {
						const dp = moment().year(this.y).month(this.m).date(1).subtract(1, 'month');
						this.render(dp.year(), dp.month());
					};
					frgCal.querySelector('.next-month').onclick = () => {
						const dn = moment().year(this.y).month(this.m).date(1).add(1, 'month');
						this.render(dn.year(), dn.month());
						//loadData(1, dn.year(), dn.month());
					};
					this.el = document.getElementById(this.id);
					this.el.innerHTML = ''; //replacing
					this.el.appendChild(frgCal);
					this.renderEvents();
				} catch (error) {
					console.error(error);
				}
			}
		});



		const ym0 = moment().format('YYYY-MM');
		const ym1 = moment().subtract(1, 'month').format('YYYY-MM');
		const ym2 = moment().add(1, 'month').format('YYYY-MM');




		const ready = callback => {
			if (document.readyState !== 'loading') callback();
			else if (document.addEventListener)
				document.addEventListener('DOMContentLoaded', callback);
			else
				document.attachEvent('onreadystatechange', function() {
					if (document.readyState === 'complete') callback();
				});
		};

		ready(async () => {
			const cal = Calendar('calendar');
			const spr = Spinner('calendar');
			await spr.renderSpinner().delay(0);
			cal.bindData(dataCalendar);
			cal.render();
		});


	}



	var mockDataJSON = <?php echo $mockDataJSON; ?>;
	loadCalendar(mockDataJSON);

</script>


<!-- ////////////////////// CALENDAR ///////////////////// -->
<!-- ///////////////////////////////////////////////////// -->