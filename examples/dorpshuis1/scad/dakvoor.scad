/*
 * Blueprint: Dak voorkant
 * description: 
 */
module dakvoor()
{
	difference() {
		union() {
		color("black") { linear_extrude(height=8) { polygon( [ 
			[-10, 0],
			[105, 0],
			[105, 43.7],
			[165, 43.7],
			[165, 0],
			[275, 0],
			[275, 166.24],
			[-10, 166.24]
		] ); } } 

		} // union


	} // difference
} // END OF: Dak voorkant

