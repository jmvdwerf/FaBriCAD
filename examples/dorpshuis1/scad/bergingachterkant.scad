/*
 * Blueprint: Achterkant van de berging
 * description: 
 */
module bergingachter()
{
	difference() {
		union() {
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[20, 96],
			[32, 96],
			[32, 104],
			[20, 104]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[73, 96],
			[85, 96],
			[85, 104],
			[73, 104]
		] ); } } 

		color("darkred") { linear_extrude(height=8) { polygon( [ 
			[0, 0],
			[105, 0],
			[105, 136],
			[0, 136]
		] ); } } 

		} // union
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[20, 72],
			[32, 72],
			[32, 96],
			[20, 96]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[73, 72],
			[85, 72],
			[85, 96],
			[73, 96]
		] ); } } 



	} // difference
} // END OF: Achterkant van de berging

