## iug-enc-dec

This repo is my way of solving assignment 01 (encryption and decryption) of the web security subject in university.

### What I Did There:

a single page app that accepts an input `text` and a method `encrypt/decrypt` and then encrypt or decrypt the inputted text.

### interface:

the design is simple, one interface with two textareas, and the methods action buttons between them.

### steps of solving:
- I defined an encryption key as a const.
- I defined the cipher algorithm as a const.
- made two functions for the two allowed methods (encrypt, decrypt), both takes a string ($data) as a param.
- the encryption function gives a base64 encrypted value of the encrypted data and the IV (for use in decryption later).
- decryption function explodes the decrypted base64 value, gets the iv, and decrypt the data using it with the const key.