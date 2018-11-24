/*
 * Blueprint: Dak achter
 * description: 
 */
module dakachter()
{
	difference() {
		union() {
		color("black") { linear_extrude(height=8) { polygon( [ 
			[-10, 0],
			[275, 0],
			[275, 166.24],
			[-10, 166.24]
		] ); } } 

		} // union


	} // difference
} // END OF: Dak achter

