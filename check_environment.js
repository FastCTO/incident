if (typeof process !== 'undefined') {
  // Running in Node.js
  console.log("Running in Node.js");
  console.log("Process version:", process.version); // Access Node.js version
  console.log("Platform:", process.platform); // Access operating system information
} else {
  // Running in a browser environment
  console.log("Running in a browser environment");
}
