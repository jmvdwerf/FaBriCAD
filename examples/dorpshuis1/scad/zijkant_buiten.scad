/*
 * Blueprint: Zijkant buiten
 * description: 
 */
module buiten()
{
	difference() {
		union() {
		color("darkred") { linear_extrude(height=8) { polygon( [ 
			[260, 0],
			[260, 135],
			[260, 192],
			[130, 267],
			[0, 192],
			[0, 0]
		] ); } } 

		} // union


	} // difference
} // END OF: Zijkant buiten

