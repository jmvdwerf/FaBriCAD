/*
 * Blueprint: Achterkant
 * description: 
 */
module achter()
{
	difference() {
		union() {
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[225, 128],
			[229.308, 144],
			[150.692, 144],
			[155, 128]
		] ); } } 

		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[97, 0],
			[105, 0],
			[105, 136],
			[97, 136]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[155, 32],
			[225, 32],
			[225, 42],
			[155, 42]
		] ); } } 

		color("darkred") { linear_extrude(height=8) { polygon( [ 
			[97, 135],
			[97, 0],
			[275, 0],
			[275, 192],
			[0, 192],
			[0, 135]
		] ); } } 

		} // union
		color("white") { linear_extrude(height=8) { polygon( [ 
			[155, 42],
			[225, 42],
			[225, 128],
			[155, 128]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[159, 99],
			[221, 99],
			[221, 124],
			[159, 124]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[159, 47],
			[221, 47],
			[221, 94],
			[159, 94]
		] ); } } 



	} // difference
} // END OF: Achterkant

