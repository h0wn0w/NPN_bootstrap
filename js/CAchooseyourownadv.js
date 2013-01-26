

var answer = prompt("are you ready to play?");
	if (answer === "yes") {
		console.log("good. next step...")
	} else {
		console.log("well, why are you here?")
	};

// var answer = confirm("are you ready to play?");


// make sure they're old enough to play
var age = prompt("What's your age?");

	if (age < "18") {
		console.log("ok, but I am indemnified");
	} else {
		console.log("great! have fun!");
	};

	console.log("Snow White and Batman were hanging out at the bus stop, waiting to go to the shops. There was a sale on and both needed some new threads. You've never really liked Batman. You walk up to him.");
	console.log("Batman glares at you.");
	var userAnswer = prompt("Are you feeling lucky, punk?");
	if (userAnswer === "yes") {
		console.log("Batman hits you very hard. It's Batman and you're you! Of course Batman wins!");
	} else {console.log("You did not say yes to feeling lucky. Good choice! You are a winner in the game of not getting beaten up by Batman.");
	}

var feedback = prompt("Please rate this game on a scale of 1 to 10.");
if (feedback > "8") {console.log("This is just the beginning of my game empire. Stay tuned for more!");
} else {console.log("I slaved away at this game and you gave me that score?! The nerve! Just you wait!");
}

