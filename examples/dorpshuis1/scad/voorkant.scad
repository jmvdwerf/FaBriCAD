/*
 * Blueprint: Front
 * description: 
 */
module front()
{
	difference() {
		union() {
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[110, 160],
			[160, 160],
			[160, 170],
			[110, 170]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[230, 128],
			[234, 144],
			[171, 144],
			[175, 128]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[120, 128],
			[124.308, 144],
			[45.6923, 144],
			[50, 128]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[50, 32],
			[120, 32],
			[120, 42],
			[50, 42]
		] ); } } 

		color("darkred") { linear_extrude(height=8) { polygon( [ 
			[0, 0],
			[275, 0],
			[275, 192],
			[165, 192],
			[165, 215],
			[135, 240],
			[105, 215],
			[105, 192],
			[0, 192]
		] ); } } 

		} // union
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[110, 170],
			[160, 170],
			[160, 216],
			[110, 216]
		] ); } } 

		color("white") { linear_extrude(height=8) { polygon( [ 
			[114, 174],
			[156, 174],
			[156, 212],
			[114, 212]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[180, 4],
			[225, 4],
			[225, 94],
			[180, 94]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[175, 4],
			[230, 4],
			[230, 128],
			[175, 128]
		] ); } } 

		color("white") { linear_extrude(height=8) { polygon( [ 
			[180, 99],
			[225, 99],
			[225, 124],
			[180, 124]
		] ); } } 

		color("white") { linear_extrude(height=8) { polygon( [ 
			[50, 42],
			[120, 42],
			[120, 128],
			[50, 128]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[54, 99],
			[116, 99],
			[116, 124],
			[54, 124]
		] ); } } 

		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[54, 47],
			[116, 47],
			[116, 94],
			[54, 94]
		] ); } } 



	} // difference
} // END OF: Front

