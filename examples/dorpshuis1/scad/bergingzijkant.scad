/*
 * Blueprint: Berging zijkant
 * description: 
 */
module berging_zijkant()
{
	difference() {
		union() {
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[100, 0],
			[108, 0],
			[108, 136],
			[100, 136]
		] ); } } 

		color("darkred") { linear_extrude(height=8) { polygon( [ 
			[0, 0],
			[108, 0],
			[108, 136],
			[0, 136]
		] ); } } 

		} // union
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[50, 4],
			[95, 4],
			[95, 100],
			[50, 100]
		] ); } } 



	} // difference
} // END OF: Berging zijkant

