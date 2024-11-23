CREATE TABLE `page_meta_with_vars` (

`id` INT(10) NOT NULL AUTO_INCREMENT ,
`page` VARCHAR(255) NOT NULL ,
`name` VARCHAR(255) NOT NULL ,
`text` VARCHAR(255) NOT NULL ,

PRIMARY KEY (`id`),
INDEX (`page`)
) ENGINE = InnoDB;


INSERT INTO `page_meta_with_vars` (`id`, `page`, `name`, `text`) VALUES

    (NULL, 'home', 'title', 'Рыночная капитализация криптовалют, курс, цена на русском языке')
    ,(NULL, 'home', 'description', 'Рейтинг криптовалютных рынков, курс, цена, графики и многое другое')

    ,(NULL, 'exchanges', 'title', 'Список бирж')
    ,(NULL, 'exchanges', 'description', 'Рейтинг криптовалютных рынков, курс, цена, графики и многое другое')

    ,(NULL, 'coins', 'title', 'Список криптовалют')
    ,(NULL, 'coins', 'description', 'Рейтинг криптовалютных рынков, курс, цена, графики и многое другое')

    ,(NULL, 'exchange', 'title', 'Криптовалютная биржа %exchangeTitle%, отзывы, рейтинг, новости на русском языке')
    ,(NULL, 'exchange', 'description', 'Узнать самую свежую информацию, новости и отзывы о бирже криптовалют %exchangeTitle%.')

    ,(NULL, 'coin', 'title', '%coinTitle% (%coinCode%) курс, цена, графики, рыночная капитализация и другие показатели')
    ,(NULL, 'coin', 'description', 'Узнать курс %coinTitle%, цену на %coinTitle% (%coinCode%), графики и другая информация о криптовалюте')

    ,(NULL, 'coin-does-not-exist', 'title', '%coinTitle% - валюта не существует')
    ,(NULL, 'coin-does-not-exist', 'description', '%coinTitle% - валюта не существует')


    ,(NULL, 'exchange-does-not-exist', 'title', '%exchangeTitle% - биржа не существует')
    ,(NULL, 'exchange-does-not-exist', 'description', '%exchangeTitle% - биржа не существует')

        ;
