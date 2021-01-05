# FaBriCAD

FaBriCAD is a simple program to generate blueprints of houses from a specific
JSON format.

Libraries used:
  - JSON for Modern C++ by Niels Lohmann (https://nlohmann.github.io/json/)
  - BOOST Geometry



To import the generated SVG into Fusion, use the following scale factor:
```
3.77965
```


# JSON format

## Project

Each JSON file represents a single project with several blueprints.

|parameter  |type                       |
|-----------|---------------------------|
|name       | string                    |
|description| string                    |
|author     | string                    |
|version    | string                    |
|licence    | string                    |
|blueprints | named array of blueprints |

## Blueprints

A blueprint contains an array of building blocks. The array is named for identification.

The first building block is used for the basis, all the other building blocks are used as cut outs for the first.

|parameter  |type                            |
|-----------|--------------------------------|
|name       | string                         |
|blocks     | named array of building blocks |

## building blocks

### Default parameters

|parameter|type       | description                   |
|---------|-----------|-------------------------------|
|name     |string     | Used to identify the element  |
|type     |string     | Defines the type of the block |
|config   |named array| Sets parameters for the block |
|shape    |Shape      | See section on Shapes         |


### Type: cutout

No parameters, is used to cutout elements from the basic form.

### Type: Brickwall

Creates a simple halfstone brick wall.

#### Configuration

The start parameter is typically used to start at a different row, to align the brick work over different walls (e.g. on corners).

|parameter  | type  | description             |
|-----------|-------|-------------------------|
|brickheight| float | Height of a brick       |
|brickwidth | float | Width of a single brick |
|start      | float | The offset to start     |

### English Bond

Creates an English bond. Contains two types of alternating rows:

1. 3/4 stone, full stone, full stone, etc.
2. 1/2 stone, ...

In dutch, this is called a 'Staand verband'.

#### Configuration

The start parameter is typically used to start at a different row, to align the brick work over different walls (e.g. on corners).

|parameter  | type  | description             |
|-----------|-------|-------------------------|
|brickheight| float | Height of a brick       |
|brickwidth | float | Width of a single brick |
|start      | float | The offset to start     |

### Lintel

```
           topR  >._____________. < topL (center + height)
                   \           /
center - width/2 >  .____.____.  < center + width / 2
                    |\   |   /|
                    | \  |  / |
                    |  \ | /  |
                    |   \|/...|
                         .   < striking point
```

#### Configuration
|parameter      | type  | description                                         |
|---------------|-------|-----------------------------------------------------|
|center         | point | The center of the lintel                            |
|striking point | point | The point used to determine the angle of the lintel |
|stones         | int   | Number of stones in one half of the lintel          |
|height         | float | The height of the lintel                            |
|width          | float | The width of the lintel                             |



### Arc Lintel

Shape should be a rectangle.


#### Configuration

|parameter  | type  | description          |
|-----------|-------|----------------------|
|brickheight| float | Height of a brick    |
|brickwidth | float | Width of a brick     |
|arclength  | float | Length of the lintel |

## shapes

### Polygon

#### Parameters

|parameter|type           |
|---------|---------------|
|points   |array of points|

#### example

```json
"shape": {
    "type": "polygon",
    "points": [
        { "x": 170, "y": 5},
        { "x": 170, "y": 130},
        { "x": 225, "y": 130},
        { "x": 225, "y": 5}
    ]
}
```

### Rectangle

#### Parameters

|Parameter|type |
|---------|-----|
|origin   |point|
|height   |float|
|width    |float|

#### Example:

```json
"shape": {
  "type": "rectangle",
  "origin": { "x": 12, "y": 14 },
  "height": 100,
  "width" : 200
}
```

### Ellipses (and circles)

For circles, use the same value for a and b. The a component defines the x-axis, the b component the y-axis.

As internally, the circle is translated into a polygon, sides defines in how many particles the circle is divided.

|Parameter|type |Default|
|---------|-----|-------|
|origin   |point|       |
|a        |float|       |
|b        |float|       |
|sides    |int  |    24 |

### Example:

```json
"shape": {
	"type": "ellipse",
	"a": 100,
	"b": 200,
	"origin": { "x": 12, "y": 14},
	"sides": 128
}
```

### Singleton

|Parameter|type |
|---------|-----|
|point    |point|

