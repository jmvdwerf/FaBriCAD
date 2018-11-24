/*
 * Blueprint: Dakkapel zijkant
 * description: 
 */
module dakkapelzijkant()
{
	difference() {
		union() {
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[0, 0],
			[23.85, 0],
			[23.85, 23],
			[0, 23]
		] ); } } 

		} // union


	} // difference
} // END OF: Dakkapel zijkant

