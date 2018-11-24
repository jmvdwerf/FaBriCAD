/*
 * Blueprint: Zijkant binnen
 * description: 
 */
module binnen()
{
	difference() {
		union() {
		} // union
		color("royalblue") { linear_extrude(height=8) { polygon( [ 
			[360, 0],
			[360, 136],
			[260, 136],
			[260, 192],
			[130, 267],
			[0, 192],
			[0, 0]
		] ); } } 



	} // difference
} // END OF: Zijkant binnen

