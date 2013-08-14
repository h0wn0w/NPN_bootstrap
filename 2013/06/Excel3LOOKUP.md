Title:		Excel Spreadsheet Building Blocks III: LOOKUP
Author:		S. Marc Testa, Ph.D.
Web:		http://neuropsychnow.com
Date:               06/20/2013 
Tags:              Data, Tables, Reports, Statistics, Multimarkdown
Base Header Level:        2

In this 3rd of 3 Excel guides (for now), we review the LOOKUP function, which is very useful if you want to LOOKUP something. Along with Part 1 of the series (which covered how to embed an equation in a spreadsheet to calculate an estimated IQ score) and Part 2 (which reviewed how to convert an IQ score to a percentile), this current article completes the series and will allow you to create your own fairly functional scoring spreadsheet.

[This spreadsheet] contains all the information you need and Figure 1 shows the content of cell J:15.
![Figure 1. LOOKUP Revealed]

If you look at the formula bar (look directly above the letters that label each column) you see this:

	=LOOKUP(C1,'BVMT-R'!$B$4:$B$17,'BVMT-R'!D4:D17)

This LOOKUP formula starts with the value in cell C1, which is age, 



[This spreadsheet]: http://d.pr/f/B1QC 
[Figure 1. LOOKUP Revealed]:../../img/lookup1.png width=800px 