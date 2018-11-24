/*
 * Blueprint: Dak achter
 * description: 
 */
module dakkapeldak()
{
	difference() {
		union() {
		color("black") { linear_extrude(height=8) { polygon( [ 
			[39.0512, 0],
			[39.0512, 34.4508],
			[-6.67, 85.1691],
			[-6.67, 0]
		] ); } } 

		} // union


	} // difference
} // END OF: Dak achter

