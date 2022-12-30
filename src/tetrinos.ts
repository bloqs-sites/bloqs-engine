type ID = string;

type Coords = {
  x: number
  y: number
}

type Shape = string;

type Shapes = Readonly<Record<number, Readonly<Shape[]>>>;

const shapes: Shapes = {
  1: [
    "#"
  ],
  2: [
    `##`
  ],
  3: [
    `###`,
    `##\n# `
  ],
  4: [
    `###\n#  `,
    `##\n##`,
    `## \n ##`,
    ` ##\n## `
  ],
  5: [
    `## \n ##\n  #`,
    `###\n#  \n#  `,
    `###\n## `,
    `###\n ##`,
  ]
} as const;

const MIN = 1;
const MAX = 5;

const rand: () => number = () => Math.floor(
  Math.random() * (MAX - MIN + 1) + MIN
);

const randShape: (squares: number) => Shape = (squares: number) => {
  const curShapes = shapes[squares];

  return curShapes[Math.floor(Math.random() * (curShapes.length))];
}

const shapeToMatrix: (shape: Shape) => String[][] = (shape: Shape) => shape
  .split(/\n/)
  .map((i: string) => i.split(""));

const COLUMNS: number = 5;
const ROWS: number = 5;

const squares: ID[][] = (function () {
  const sqrs = [];
  
  for (let i: number = 0; i < COLUMNS; i++) {
    sqrs.push(new Array<ID>(ROWS).fill("."));
  }

  return sqrs;
})();

let id: ID = "A";

const rotateMatrix = (matrix: any[][]) => matrix[0]
  .map((_, i) => matrix.map(row => row[i]))
  .map(row => row.reverse());

function putShape({x, y}: Coords, shape: String[][]): void {
  for (let i = 0; i < shape.length; i++) {
    for (let j = 0; j < shape[i].length; j++) {
      if (shape[i][j] != " ") {
        squares[y + i][x + j] = id;
      }
    }
  }

  id = String.fromCharCode(id.charCodeAt(0) + 1);
}

function putSquare(squares: ID[][], n?: number): boolean {
  n ??= rand();

  let shape = shapeToMatrix(randShape(n));

  for (let x: number = Math.floor(Math.random() * 4); x > 0; x--) {
    shape = rotateMatrix(shape);
  }

  for (let y: number = 0; y < 4; y++) {
    for (let i: number = 0; i < squares.length/* - shape.length*/; i++) {
      next: for (let j: number = 0; j < (squares[i].length/* - shape[0].length*/); j++) {
        for (let k: number = 0; k < shape.length; k++) {
          for (let l: number = 0; l < shape[k].length; l++) {
            if (squares[i + k]?.[j + l] == undefined || (squares[i + k][j + l] != "." && shape[k][l] != " ")) {
              continue next;
            }

            if (k == shape.length - 1 && l == shape[k].length - 1) {
              putShape({
                x: j,
                y: i,
              }, shape);

              return true;
            }
          }
        }
      }
    }

    shape = rotateMatrix(shape);
  }

  return false;
}

while(putSquare(squares));

while(putSquare(squares, 1));

console.log(squares.map(i => i.join("")).join("\n"), "\n");
