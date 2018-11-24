/*
 * Blueprint: Floor
 * description: 
 */
module oppervlak()
{
	difference() {
		union() {
		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[223, 260],
			[267, 260],
			[267, 352],
			[223, 352]
		] ); } } 

		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[178, 260],
			[218, 260],
			[218, 312],
			[178, 312]
		] ); } } 

		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[8, 133],
			[165, 133],
			[165, 252],
			[8, 252]
		] ); } } 

		color("royalblue") { linear_extrude(height=2) { polygon( [ 
			[0, 0],
			[275, 0],
			[275, 360],
			[170, 360],
			[170, 260],
			[0, 260]
		] ); } } 

		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[170, 8],
			[267, 8],
			[267, 128],
			[170, 128]
		] ); } } 

		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[170, 133],
			[267, 133],
			[267, 252],
			[170, 252]
		] ); } } 

		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[178, 317],
			[218, 317],
			[218, 352],
			[178, 352]
		] ); } } 

		color("royalblue") { linear_extrude(height=3) { polygon( [ 
			[8, 8],
			[165, 8],
			[165, 128],
			[8, 128]
		] ); } } 

		} // union


	} // difference
} // END OF: Floor

