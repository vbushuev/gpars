
SELECT g_categories,brand,title,g_title,description,g_description,url FROM xr_g_product where shop='brandalley';
delete from xr_g_dictionary where lang='fr';
insert into xr_g_dictionary(lang,Original,translate,priority) values
    ('fr','2 poches devant','2 передних кармана',30),
    ('fr','Accessoire','аксессуар',0),
    ('fr','Accessoires','аксессуары',1),
    ('fr','Accessoires Chaussure','Аксессуары для обуви',0),
    ('fr','Accessoires High Tech','High Tech аксессуары',0),
    ('fr','Accessoires High-Tech','High-Tech аксессуары',0),
    ('fr','Accessoires iPhone','iPhone аксессуары',0),
    ('fr','acrylique','акрил',0),
    ('fr','Aspect délavé','вымываются',20),
    ('fr','Babie','дети',0),
    ('fr','Babies','дети',1),
    ('fr','Bagagerie','багаж',0),
    ('fr','Ballerine','Балетки',0),
    ('fr','Ballerines','Балетки',1),
    ('fr','Basket','кеды',0),
    ('fr','Baskets','кеды',1),
    ('fr','Bermuda','бермуды',0),
    ('fr','Bermudas','бермуды',1),
    ('fr','Blazer','блейзер',0),
    ('fr','Blazers','блейзеры',1),
    ('fr','Bleu','синий',0),
    ('fr','Blouse','блузка',0),
    ('fr','Blouses','Блузы',1),
    ('fr','Blouson','короткая куртка',0),
    ('fr','Blousons','короткие куртки',1),
    ('fr','Bodie','Боди',0),
    ('fr','Bodies','Боди',1),
    ('fr','Bonnet','лыжная шапочка',0),
    ('fr','Bonnets','лыжные шапочки',1),
    ('fr','Boot','ботинок',0),
    ('fr','Boots','ботинки',1),
    ('fr','Botte','ботинок',0),
    ('fr','Bottes','ботинки',1),
    ('fr','Bottillon','ботильон',0),
    ('fr','Bottillons','ботильоны',1),
    ('fr','Boutons de manchette','Запонки',0),
    ('fr','Boutons de Manchette','Запонки',0),
    ('fr','Boxer','боксеры',0),
    ('fr','Boxers','Боксеры',1),
    ('fr','Bretelles larges','широкие ремни',10),
    ('fr','Capuche avec fourrure véritable','Капюшон с натуральным мехом',40),
    ('fr','CARACO','топ',0),
    ('fr','Cartable','портфель',0),
    ('fr','Casquette','кепка',0),
    ('fr','Casquettes','кепка',1),
    ('fr','Ceinture','ремень',0),
    ('fr','Ceintures','Ремни',1),
    ('fr','Chèche','шарфик',0),
    ('fr','Chapeaux','головные уборы',0),
    ('fr','Chaussette','носок',0),
    ('fr','Chaussettes','носки',1),
    ('fr','Chausson','тапок',0),
    ('fr','Chaussons','тапки',1),
    ('fr','Chaussure','обувь',0),
    ('fr','chaussures','обувь',1),
    ('fr','Chaussures','обувь',1),
    ('fr','Chaussures bateau','Лодочная обувь',0),
    ('fr','Chaussures Bateau','Лодочная обувь',0),
    ('fr','Chaussures de sport','Спортивная обувь',0),
    ('fr','Chaussures lacée','Обувь lacce',0),
    ('fr','Chaussures montante','Обувь высокая',0),
    ('fr','Chemise','рубашка',0),
    ('fr','CHEMISE DE NUIT','ночная рубашка',0),
    ('fr','Chemises','Рубашки',1),
    ('fr','Chemisier','блузка',0),
    ('fr','Col à capuche','воротник с капюшоном',20),
    ('fr','Col à revers','Воротник отворотом',20),
    ('fr','Col montant','воротник высокий',10),
    ('fr','Col rond','воротник круглый',10),
    ('fr','Collant','трико',0),
    ('fr','Collants','трико',1),
    ('fr','COMBI','комби',0),
    ('fr','Combinaison','комбинезон',0),
    ('fr','Combinaisons','комбинезоны',1),
    ('fr','COMBINETTE','комбинация',0),
    ('fr','Composition','состав',0),
    ('fr','Composition doublure','состав подкладки',10),
    ('fr','Corps','тело',1),
    ('fr','CORSAIRE','короткие спортивные штаны',0),
    ('fr','Costume','костюм',0),
    ('fr','Costumes','костюмы',1),
    ('fr','coton','хлопок',0),
    ('fr','Couleur','цвет',0),
    ('fr','Coupe ajustée','прилегающий покрой',10),
    ('fr','Coupe droite','прямой покрой',10),
    ('fr','court','короткая',0),
    ('fr','Cravate','галстук',0),
    ('fr','Cravates','галстуки',1),
    ('fr','Cuissarde','лосины',0),
    ('fr','Cuissardes','лосины',1),
    ('fr','Culotte','трусики',0),
    ('fr','Culottes','трусики',1),
    ('fr','Derbie','дерби',0),
    ('fr','Derbies','дерби',1),
    ('fr','Doudoune','жакет',0),
    ('fr','Doudounes','жакеты',1),
    ('fr','Echarpe','шарф',0),
    ('fr','Echarpes','шарфов',1),
    ('fr','ecru','серовато-бежевый',0),
    ('fr','élasthanne','эластана',0),
    ('fr','Ensemble','комплект',0),
    ('fr','Ensembles','комплекты',1),
    ('fr','Escarpin','туфли-лодочки',0),
    ('fr','Escarpins','туфли-лодочки',1),
    ('fr','Espadrille','эспадрильи',0),
    ('fr','Espadrilles','эспадрильи',1),
    ('fr','Fente dans le dos','Разрез сзади',40),
    ('fr','Fermeture boutonnée et zippée devant','застежка кнопка и молния спереди',40),
    ('fr','fermeture zippée','застежка-молния',20),
    ('fr','Foulard','шарф',0),
    ('fr','Foulards','шарфы',1),
    ('fr','Fournitures scolaire','школьные принадлежности',0),
    ('fr','Gant','перчатка',0),
    ('fr','Gants','перчатки',1),
    ('fr','Gilet','куртка',0),
    ('fr','Gilets','куртки',1),
    ('fr','GUÃPIÃRE','корсет',0),
    ('fr','Homewear','Домашняя одежда',0),
    ('fr','JEAN','джинсы',0),
    ('fr','Jean','джинсы',0),
    ('fr','JEAN SKINNY','узкие джинсы',0),
    ('fr','Jeans','джинсы',1),
    ('fr','Jeu de surpiqûres','Игра',20),
    ('fr','Jupe','юбка',0),
    ('fr','Jupes','подол',1),
    ('fr','Kaki','хаки',0),
    ('fr','latérale(s)','сторона (ы)',10),
    ('fr','Lavage à la machine recommandé à 30°','рекомендованая машинная стирка при 30 ° ',50),
    ('fr','LEGGING','леггинсы',0),
    ('fr','Legging','леггинсы',0),
    ('fr','Leggings','леггинсы',1),
    ('fr','Lingerie Sexy','Сексуальное белье',0),
    ('fr','Longueur','длина',0),
    ('fr','Lunettes de soleil','темные очки',0),
    ('fr','Lunettes de Soleil','темные очки',0),
    ('fr','Maillots','Купальники',1),
    ('fr','Maillots de Bain','купальный костюм',0),
    ('fr','Maillots de bain','купальный костюм',0),
    ('fr','Manches longues','длинные рукава',10),
    ('fr','MANTEAU','манто',0),
    ('fr','Manteaux','манто',0),
    ('fr','Mocassin','мокасин',0),
    ('fr','Mocassins','мокасины',1),
    ('fr','modal','модальный',0),
    ('fr','montantes','поднимающийся',1),
    ('fr','Motif','мотив',0),
    ('fr','Noeuds Papillon','бабочка',0),
    ('fr','Noir','черный',0),
    ('fr','NUISETTE','комбинация',0),
    ('fr','Pantalon','брюки',0),
    ('fr','Pantalons','брюки',1),
    ('fr','Papillons','бабочки',1),
    ('fr','Paréo','парео',0),
    ('fr','Paréos','парео',1),
    ('fr','Parapluie','зонт',0),
    ('fr','Parapluies','зонты',1),
    ('fr','Petite Maroquinerie','кожгалантерея',0),
    ('fr','Pilote','куртка пилот',0),
    ('fr','Pilotes','куртки пилот',1),
    ('fr','Poignets boutonnés','манжеты на пуговицах',10),
    ('fr','Poignets patte boutonnée','манжеты на пуговицах',20),
    ('fr','Polo','поло',0),
    ('fr','Polos','поло',1),
    ('fr','polyamide','полиамид',0),
    ('fr','polyester','полиэстер',0),
    ('fr','Portefeuille','портфель',0),
    ('fr','Pull','свитер',0),
    ('fr','Pulls','Свитера',1),
    ('fr','Pyjama','пижама',0),
    ('fr','Pyjamas','пижамы',1),
    ('fr','Richelieu','Ришелье',0),
    ('fr','Richelieus','Ришелье',1),
    ('fr','Robe','платье',0),
    ('fr','Robes','Платья',1),
    ('fr','Sac','сумка',0),
    ('fr','Sacoche','кринолин',0),
    ('fr','Sacs','сумки',1),
    ('fr','Salomé','застежка',0),
    ('fr','Salopette','комбинезон',0),
    ('fr','Salopettes','комбинезоны',1),
    ('fr','Sandale','сандалии',0),
    ('fr','Sandales','сандалии',1),
    ('fr','Sans manches','без рукавов',10),
    ('fr','scolaires','школьный',1),
    ('fr','Short','короткий',0),
    ('fr','Shortie','шорты',0),
    ('fr','Shorties','шорты',1),
    ('fr','Shorts','шорты',1),
    ('fr','Slip','трусы',0),
    ('fr','Slips','трусы',1),
    ('fr','Sneaker','кроссовки',0),
    ('fr','Sneakers','кроссовки',1),
    ('fr','Sous-vêtements','нижнее белье',1),
    ('fr','Soutiens-gorge','Бюстгальтеры',0),
    ('fr','String','стринги',0),
    ('fr','Strings','стринги',1),
    ('fr','Sweat','толстовки',0),
    ('fr','Sweats','толстовки',1),
    ('fr','T-Shirt','Футболка',0),
    ('fr','T-Shirts','Футболки',1),
    ('fr','texte, imprimé sur manche et dans le dos','Текст, принт',50),
    ('fr','Tong','вьетнамки',0),
    ('fr','Tongs','вьетнамки',1),
    ('fr','Top','топ',0),
    ('fr','Tops','топы',1),
    ('fr','Tous les Maillots de Bain','Все купальники',0),
    ('fr','TROUSSE DE TOILETTE','косметичка',0),
    ('fr','Tunique','Туника',0),
    ('fr','Tuniques','Туники',1),
    ('fr','Veste','куртка',0),
    ('fr','Veste chaude','теплая куртка',10),
    ('fr','Vestes','Куртки',1),
    ('fr','viscose','вискоза',0),
    ('fr','Coupe cintrée','Отрезная талия',10),
    ('fr','Motif : fantaisie all over','Мотив : фантазии во всем',20),
    ('fr','tacheté','пятнистый',0),
    ('fr','Col tailleur','воротник костюмный',10),
    ('fr','Fermeture ajustable derrière','Застежка регулируемая сзади',20);


update xr_g_product set title = replace(title,'Ãª','ê'),description = replace(description,'Ãª','ê') where shop = 'brandalley';