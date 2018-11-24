/*
 * Blueprint: Dak berging
 * description: 
 */
module bergingdak()
{
	difference() {
		union() {
		color("black") { linear_extrude(height=8) { polygon( [ 
			[0, 0],
			[110, 0],
			[110, 110],
			[0, 110]
		] ); } } 

		} // union


	} // difference
} // END OF: Dak berging

