/*
 * jQuery dateCompletion plugin
 * Version 0.9.1  (3/12/2007)
 *
 * Licensed under the GPL licenses:
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/*
 * @name dateCompletion
 * @type jQuery
 * @cat Plugins/forms
 * @author Jon Ramvi
 */
 
/*
 * Changelog
 * 0.9.1
 	Doesn't do anything when the inputed month is over 12
	Fixed bug where 24-10-07 would return 2010-10-24
 * 0.9.0 Released
 */



jQuery.fn.dateCompletion = function(text) {
  return this.each(function(){
	

	var to = text;
	var toLength = to.length;
	var firstDash = to.indexOf("-");

	var d = new Date(); //The date today
	var month = d.getMonth(); //Putting the month in a var
	var month = month + 1; //The month is by default, this month
	var thisMonth = month;
	var today = d.getDate();
	var year = d.getFullYear();

	//Found dash
	if (firstDash != -1) {
		var dateArray = to.split("-");
		
		if (dateArray.length <= 3) { //If there's like 4 entires / seperators, there isn't much it can do...
			
			if ((dateArray[0] <= 31) && (dateArray[1] <= 12)) { //Just skip everything if the day is over 31 or the month is over 12
				//DAY
				if (dateArray[0].length == 1) { //Leading zero
					var day = '0' + dateArray[0];
				}
				else if (dateArray[0].length == 2) {
					var day = dateArray[0];
				}
				
				//MONTH
				if (dateArray[1].length == 1) { //Leading zero
					month = '0' + dateArray[1];
				}
				else if (dateArray[1].length == 2) {
					month = dateArray[1];
				}
				
				//YEAR
				if (dateArray[2]) {
					if (dateArray[2].length == 2) { //Leading stuff
						year = '20' + dateArray[2];
					}
					else if (dateArray[2].length == 4) {
						year = dateArray[2];
					}
				} else {
				
					if (month < thisMonth) {
						year = 1 + year;
					}
				
				}
				
			}
			
		}
		
	}
	
	//No seperator was found
	else {
		
		if ((toLength == 1) || (toLength == 2)) {
			if (to <= 31) { //Just skip everything if the first two chars are above 31
			
				if (toLength == 1) { //Leading zero
					var day = '0' + to;
				}
				if (toLength == 2) { //the day is what was typed
					var day = to;
				}
				
				if (day < today) {
					if (month == 12) {
						month = '01';
						year = 1 + year;
					} else {
						month = 1 + month;
					}
				}
				

				
			}
		}
		
	}

	to = month + '-' + day + '-' + year;
	if (!day) { //Avoids returning undelcared info if nothing was found
		to = "error";
	} else {
		$(this).val(to);
	}
	//return to;
	
	
	
  });
};
jQuery.log = function(message) {
  if(window.console) {
     console.debug(message);
  } else {
     alert(message);
  }
};