# EZE-DATABASE

Dynamic database class written in PHP (OOP)

(C) 2017 - Biznatch Enterprises (Biznaturally.ca)

Version 2.0 is currently in development

- NEW FILE FORMAT: <*label*[data]&*label*[data]>
- COMPLETLY REWRITTEN

"Raw Data" is stored in a similar way as a Hard Drive. Database files (Drive IDs) contains Partitions accociated with Sectors of individual data. These Data Sectors within Partitions are stored in a flat-file with unique identifiers and can be individually encrypted with unique keys or "linked" by hashing or other means... To sectors contained in partitions stored in seperate database files.

Multiple databases can be loaded concurrently as unique "objects" within the class, accessing their methods (functions) individually.

Once parsed from the database: All data can be read by a PHP script by using a double array. $DATABASE[partition][sector]

# Contribute to development
Please donate cryptocoins:

- Bitcoin: 1Mc9ZxuhH5nMBCbaikhpeuWLaS55hAy2xj
- BitConnect: 8HYuunQqthXvLLD4h1kQwnNFLJHZRx6Xx7
- Litcoin: Lcj3raz2GwWKAgYTBXwfS2Tzr2hLstXrXZ
- Peercoin: PLAtqFPWs5BHYvnNfjajpzdXfS9K499LbW
- Bata: BPPbTF9vrQuJvaSrYgvmgYq7v9Wbw1U5XT
- Dash: Xe1UeYxjgDoEJYfr2VqWC2nVnR3EPAxfNq
- Stratis: Sbfm49D8wnQqmopqYxuTkpyWp1mtizM2UR
- Pivx: DNRraJPkEAtrvM8zp9VL5SQYs2Yvt7zrbT
- Zcoin: aKUmKWqKiW5GYBy8McxjudxbSvbtWFfa94
- Crypto Bullion: 5Y5dv3RPp2e8Pt3yD1v8nQ4LGPjcSyep7p
- GoldCoin: DzcAKmTtNUA1V7HzoF4xtaLuBvKiAt5ZXe

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
