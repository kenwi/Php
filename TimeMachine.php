<?php

abstract class renderObject
{
	// Hvilken type objekt dette er
	protected $type;

	// Når skal objektet begynne å eksistere i tid
	protected $spawnWhen;

	// Dette vil typisk være en rendermetode,
	// Dersom det er forskjellige ting som må gjøres ut i fra hvilket objekt det er snakk om
	// Så kan denne settes til abstract og implementeres i de arvede klassene
	public function printType()
	{
		echo "A " . $this->type . "\n";
	}

	public function getType()
	{
		return $this->type;		
	}

	public function whenWillISpawn()
	{
		return $this->spawnWhen;
	}

	public function shouldSpawnAt( $seconds )
	{
		$this->spawnWhen = $seconds;
	}
}

class cubeObject extends renderObject
{
	public function __construct()
	{
		// Sett objekttypen
		$this->type = "Cube";
	}
}

class squareObject extends renderObject
{
	public function __construct()
	{
		// Sett objekttypen
		$this->type = "Square";
	}	
}

// Si ordet "tidsmaskin" til en engelskmann. Hirr
class titsMachine 
{
	// Nåværende tid
	private $currentTime;

	// Samlingsarray for alle objekter
	private $objects;

	public function __construct()
	{
		// Opprett array for alle objekter
		$this->objects = array();

		// Nåværende tid for simulator
		$this->currentTime = 0;
	}

	public function whatTimeIsIt()
	{
		echo "Current time is " . $this->currentTime . "\n";
	}

	public function travelToTime( $seconds )
	{
		echo "Warps to " . $seconds . "\n";
		
		// Reis i tid
		$this->currentTime = $seconds;
	}
	
	public function printObservableObjects()
	{
		echo "Existing objects at time " . $this->currentTime . "\n";

		// Loop igjennom alle objekter i objektarrayet og sjekk om nåværende tid er høyere eller lik 
		// tiden for når objektet skal eksistere
		foreach ($this->objects as $key => $value) 
		{
			if( $value->whenWillISpawn() <= $this->currentTime )	
			{
				$value->printType();
			}
		}
	}

	public function placeObject( $object )
	{
		echo "Placing object " . $object->getType() . " in time " . $this->currentTime . "\n";
		
		// Sett tidspunktet objektet skal eksistere fra og legg til objektet i objektarray
		$object->shouldSpawnAt( $this->currentTime );
		$this->objects[] = $object;
	}

	public function runSimulation()
	{
		// Print ut hva klokken er
		$this->whatTimeIsIt();
		
		// Legg inn et objekt i nåværende tid (0)
		$this->placeObject( new squareObject() );

		// Se hvilke objekter som eksisterer i denne tiden
		$this->printObservableObjects();

		// Reis framover i tid og legg objektet i framtiden (5)
		$this->travelToTime( 5 );
		$this->placeObject( new cubeObject() );

		// Reis tilbake i tid
		$this->travelToTime( 0 );

		// Se hvilke objekter som eksisterer i denne tiden (0)
		$this->printObservableObjects();

		// La tiden gå, og print ut hvert objekt som kan sees
		for( $i=0; $i<10; $i++ )
		{
			$this->currentTime++;
			$this->printObservableObjects();
		}		
	}
}

$tits = new titsMachine();
$tits->runSimulation();

?>