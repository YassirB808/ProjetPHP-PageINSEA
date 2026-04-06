-- Data Migration for INSEA Website
-- Run this AFTER database_setup.sql

USE insea_db;

-- Clear existing data (safer with DELETE than TRUNCATE for FK constraints)
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM news_translations;
DELETE FROM news;
DELETE FROM job_offers_translations;
DELETE FROM job_offers;
DELETE FROM partners;
DELETE FROM filieres_translations;
DELETE FROM filieres;
DELETE FROM site_content;
DELETE FROM gallery;
DELETE FROM student_life_translations;
DELETE FROM student_life;
DELETE FROM graduations_translations;
DELETE FROM graduations;
SET FOREIGN_KEY_CHECKS = 1;

-- 5. GALLERY MIGRATION
INSERT INTO gallery (image_url, link_url, category) VALUES 
('others/insea_home.jpg', 'https://insea.ac.ma', 'evenements'),
('others/insea_home_image2.png', NULL, 'etudiants'),
('partenariats/hcp_logo.png', 'https://www.hcp.ma', 'partenariats');

SET @gal1 = LAST_INSERT_ID();
SET @gal2 = @gal1 + 1;
SET @gal3 = @gal1 + 2;

INSERT INTO gallery_translations (gallery_id, language_id, title) VALUES
(@gal1, 1, 'Campus INSEA'), (@gal1, 2, 'INSEA Campus'), (@gal1, 3, 'حرم المعهد'),
(@gal2, 1, 'Vie Étudiante'), (@gal2, 2, 'Student Life'), (@gal2, 3, 'الحياة الطلابية'),
(@gal3, 1, 'Partenaire HCP'), (@gal3, 2, 'HCP Partner'), (@gal3, 3, 'شريك المندوبية السامية للتخطيط');

-- 1. NEWS MIGRATION
-- News 1
INSERT INTO news (publish_date, is_featured) VALUES ('2026-03-02', TRUE);
SET @news1 = LAST_INSERT_ID();
INSERT INTO news_translations (news_id, language_id, title, content) VALUES
(@news1, 1, 'Transformation digitale : L\'INSEA inaugure son nouveau laboratoire de Data Science', 'Ce nouvel espace permettra aux étudiants et chercheurs de travailler sur des projets innovants en utilisant les dernières technologies de pointe.'),
(@news1, 2, 'Digital Transformation: INSEA inaugurates its new Data Science laboratory', 'This new space will allow students and researchers to work on innovative projects using the latest advanced technologies.'),
(@news1, 3, 'التحول الرقمي: المعهد يدشن مختبره الجديد لعلوم البيانات', 'سيتيح هذا الفضاء الجديد للطلاب والباحثين العمل على مشاريع مبتكرة باستخدام أحدث التقنيات المتطورة.');

-- News 2
INSERT INTO news (publish_date, is_featured) VALUES ('2026-02-25', FALSE);
SET @news2 = LAST_INSERT_ID();
INSERT INTO news_translations (news_id, language_id, title, content) VALUES
(@news2, 1, 'Concours d\'accès 2026/2027', 'Les inscriptions sont désormais ouvertes pour les cycles ingénieurs et masters.'),
(@news2, 2, 'Access Contest 2026/2027', 'Registrations are now open for engineering and masters cycles.'),
(@news2, 3, 'مباراة الولوج 2026/2027', 'التسجيلات مفتوحة الآن لسلك المهندسين وسلك الماستر.');

-- News 3
INSERT INTO news (publish_date, is_featured) VALUES ('2026-02-15', FALSE);
SET @news3 = LAST_INSERT_ID();
INSERT INTO news_translations (news_id, language_id, title, content) VALUES
(@news3, 1, 'Séminaire sur l\'IA et l\'Économie', 'Retour sur la conférence exceptionnelle tenue par le Professeur Alan Smith.'),
(@news3, 2, 'Seminar on AI and Economics', 'Report on the exceptional conference held by Professor Alan Smith.'),
(@news3, 3, 'ندوة حول الذكاء الاصطناعي والاقتصاد', 'تقرير عن المحاضرة الاستثنائية التي ألقاها البروفيسور آلان سميث.');

-- 2. PARTNERS MIGRATION
INSERT INTO partners (name, logo_url, type) VALUES 
('HCP', 'partenariats/hcp_logo.png', 'national'),
('BCP', 'partenariats/Logo_BCP.png', 'national'),
('DGCL', 'partenariats/Logo_DGCL.png', 'national'),
('ENSAI', 'partenariats/Logo_ENSAIsvg.svg', 'international'),
('UCLouvain', 'partenariats/UCLouvain_Logo.png', 'international'),
('UNFPA', 'partenariats/UNFPA_logo.svg', 'international');

-- 3. FILIERES MIGRATION
-- AF
INSERT INTO filieres (code_key) VALUES ('af');
SET @fil_af = LAST_INSERT_ID();
INSERT INTO filieres_translations (filiere_id, language_id, title, description) VALUES
(@fil_af, 1, 'Actuariat-Finance', 'La filière Actuariat-Finance forme des ingénieurs capables de modéliser les risques financiers et d\'assurance. Elle combine des compétences pointues en mathématiques stochastiques, en finance de marché et en gestion des risques.'),
(@fil_af, 2, 'Actuarial-Finance', 'The Actuarial-Finance specialization trains engineers capable of modeling financial and insurance risks. It combines advanced skills in stochastic mathematics, market finance, and risk management.'),
(@fil_af, 3, 'الاكتوارية والمالية', 'تكون شعبة الاكتوارية والمالية مهندسين قادرين على نمذجة المخاطر المالية والتأمينية. وتجمع بين مهارات متقدمة في الرياضيات العشوائية ومالية الأسواق وإدارة المخاطر.');

-- DS
INSERT INTO filieres (code_key) VALUES ('ds');
SET @fil_ds = LAST_INSERT_ID();
INSERT INTO filieres_translations (filiere_id, language_id, title, description) VALUES
(@fil_ds, 1, 'Data Science', 'La filière Data Science forme des experts en extraction de connaissances à partir de données massives. Elle couvre le machine learning, le deep learning, le big data et l\'intelligence artificielle.'),
(@fil_ds, 2, 'Data Science', 'The Data Science specialization trains experts in extracting knowledge from massive data. It covers machine learning, deep learning, big data, and artificial intelligence.'),
(@fil_ds, 3, 'علوم البيانات', 'تكون شعبة علوم البيانات خبراء في استخراج المعرفة من البيانات الضخمة. وتغطي تعلم الآلة والتعلم العميق والبيانات الضخمة والذكاء الاصطناعي.');

-- 4. SITE CONTENT MIGRATION (General translations)
-- Welcome Title
INSERT INTO site_content (content_key, language_id, content_value) VALUES 
('welcome_title', 1, 'Bienvenue à l\'INSEA'),
('welcome_title', 2, 'Welcome to INSEA'),
('welcome_title', 3, 'مرحباً بكم في المعهد الوطني للإحصاء والاقتصاد التطبيقي');

-- Welcome Text
INSERT INTO site_content (content_key, language_id, content_value) VALUES 
('welcome_text', 1, 'L\'Institut National de Statistique et d\'Economie Appliquée (INSEA) est une grande école d\'ingénieurs marocaine créée en 1961. Il a pour mission la formation d\'ingénieurs d\'État et de cadres de haut niveau dans les domaines de la statistique, de l\'économie appliquée, de l\'informatique et de la science des données.'),
('welcome_text', 2, 'The National Institute of Statistics and Applied Economics (INSEA) is a leading Moroccan engineering school created in 1961. Its mission is to train state engineers and high-level executives in the fields of statistics, applied economics, computer science, and data science.'),
('welcome_text', 3, 'المعهد الوطني للإحصاء والاقتصاد التطبيقي (INSEA) هو مدرسة كبرى للمهندسين بالمغرب تأسست عام 1961. وتتمثل مهمته في تكوين مهندسي الدولة وأطر عليا في مجالات الإحصاء والاقتصاد التطبيقي والمعلوميات وعلوم البيانات.');

-- Director Quote
INSERT INTO site_content (content_key, language_id, content_value) VALUES 
('director_quote', 1, '"À l\'INSEA, nous nous engageons à former les leaders de demain. Notre ambition est d\'offrir un environnement académique d\'excellence, propice à l\'innovation, à la recherche et au développement de compétences pointues en data science, statistique et économie appliquée, répondant ainsi aux défis d\'un monde en perpétuelle mutation."'),
('director_quote', 2, '"At INSEA, we are committed to training the leaders of tomorrow. Our ambition is to offer an academic environment of excellence, conducive to innovation, research and the development of specialized skills in data science, statistics and applied economics, thus meeting the challenges of a constantly changing world."'),
('director_quote', 3, '"في المعهد، نلتزم بتكوين قادة الغد. طموحنا هو تقديم بيئة أكاديمية متميزة، مواتية للابتكار والبحث وتطوير مهارات متقدمة في علوم البيانات والإحصاء والاقتصاد التطبيقي، استجابة لتحديات عالم دائم التغير."');

-- Add more as needed...

-- 6. JOBS MIGRATION
INSERT INTO job_offers (post_date) VALUES ('2026-03-01'), ('2026-02-20');
SET @job1 = LAST_INSERT_ID();
SET @job2 = @job1 + 1;

INSERT INTO job_offers_translations (job_offer_id, language_id, title, content) VALUES
(@job1, 1, 'Data Scientist Senior - Secteur Bancaire', 'Une grande institution financière recherche un ingénieur INSEA spécialisé en Data Science pour piloter des projets de machine learning.'),
(@job1, 2, 'Senior Data Scientist - Banking Sector', 'A large financial institution is looking for an INSEA engineer specialized in Data Science to lead machine learning projects.'),
(@job1, 3, 'عالِم بيانات أول - القطاع المصرفي', 'تبحث مؤسسة مالية كبرى عن مهندس من المعهد متخصص في علوم البيانات لقيادة مشاريع تعلم الآلة.'),
(@job2, 1, 'Actuaire Consultant - Cabinet International', 'Recrutement de profils Actuariat-Finance pour des missions d\'audit et de conseil en gestion des risques.'),
(@job2, 2, 'Actuarial Consultant - International Firm', 'Recruitment of Actuarial-Finance profiles for audit and risk management consulting missions.'),
(@job2, 3, 'خبير اكتواري استشاري - شركة دولية', 'توظيف متخصصين في الاكتوارية والمالية لمهام التدقيق والاستشارة في إدارة المخاطر.');

-- 7. EVENTS MIGRATION
INSERT INTO events (event_date) VALUES ('Septembre 2025'), ('Janvier 2026'), ('Fin Janvier 2026'), ('Février 2026'), ('Juin 2026'), ('Juillet 2026');
SET @event1 = LAST_INSERT_ID();
SET @event2 = @event1 + 1;
SET @event3 = @event1 + 2;
SET @event4 = @event1 + 3;
SET @event5 = @event1 + 4;
SET @event6 = @event1 + 5;

INSERT INTO events_translations (event_id, language_id, title) VALUES
(@event1, 1, 'Rentrée universitaire'), (@event1, 2, 'University Re-entry'), (@event1, 3, 'الدخول الجامعي'),
(@event2, 1, 'Examens de fin du 1er semestre'), (@event2, 2, '1st Semester Final Exams'), (@event2, 3, 'امتحانات نهاية الفصل الأول'),
(@event3, 1, 'Vacances de fin de semestre'), (@event3, 2, 'End of Semester Holidays'), (@event3, 3, 'عطلة نهاية الفصل'),
(@event4, 1, 'Début du 2ème semestre'), (@event4, 2, 'Start of 2nd Semester'), (@event4, 3, 'بداية الفصل الثاني'),
(@event5, 1, 'Examens de fin d\'année'), (@event5, 2, 'End of Year Exams'), (@event5, 3, 'امتحانات نهاية السنة'),
(@event6, 1, 'Rattrapages'), (@event6, 2, 'Make-up Exams'), (@event6, 3, 'امتحانات الاستدراك');

-- 8. LABORATORIES MIGRATION
INSERT INTO laboratories () VALUES (), (), (), ();
SET @lab1 = LAST_INSERT_ID();
SET @lab2 = @lab1 + 1;
SET @lab3 = @lab1 + 2;
SET @lab4 = @lab1 + 3;

INSERT INTO laboratories_translations (lab_id, language_id, name) VALUES
(@lab1, 1, 'Laboratoire de Recherche en Économie Appliquée (LAREA)'),
(@lab1, 2, 'Applied Economics Research Laboratory (LAREA)'),
(@lab1, 3, 'مختبر البحث في الاقتصاد التطبيقي'),
(@lab2, 1, 'Laboratoire de Statistique et de Processus Aléatoires (LSPA)'),
(@lab2, 2, 'Statistics and Stochastic Processes Laboratory (LSPA)'),
(@lab2, 3, 'مختبر الإحصاء والعمليات العشوائية'),
(@lab3, 1, 'Laboratoire d\'Informatique et de Recherche Opérationnelle (LIRO)'),
(@lab3, 2, 'Computer Science and Operations Research Laboratory (LIRO)'),
(@lab3, 3, 'مختبر المعلوميات والبحث العملياتي'),
(@lab4, 1, 'Laboratoire de Data Science et d\'IA (LDSIA)'),
(@lab4, 2, 'Data Science and AI Laboratory (LDSIA)'),
(@lab4, 3, 'مختبر علوم البيانات والذكاء الاصطناعي');

-- 9. STUDENT LIFE MIGRATION
INSERT INTO student_life (category_slug) VALUES ('internat'), ('library'), ('foyer'), ('adei'), ('clubs'), ('sports'), ('health');
SET @sl_internat = LAST_INSERT_ID();
SET @sl_library = @sl_internat + 1;
SET @sl_foyer = @sl_internat + 2;
SET @sl_adei = @sl_internat + 3;
SET @sl_clubs = @sl_internat + 4;
SET @sl_sports = @sl_internat + 5;
SET @sl_health = @sl_internat + 6;

INSERT INTO student_life_translations (student_life_id, language_id, title, content) VALUES
-- Internat & Restauration
(@sl_internat, 1, 'Internat & Restauration', 'Restauration : L\'INSEA est dotée depuis octobre 1994, d’un restaurant avec une capacité d’accueil de près de 600 places. Il sert en moyenne 1.200 repas par jour (petit déjeuner, déjeuner et dîner).\n\nInternat : L’Institut dispose d’un internat réputé parmi les meilleurs à Rabat. Il comprend 5 bâtiments avec plus de 500 chambres individuelles et doubles. Services pratiques : Buvette, épicerie, centre de photocopie, blanchisserie et cabinet médical.'),
(@sl_internat, 2, 'Internship & Restoration', 'Catering: Since 1994, INSEA has a restaurant with 600 seats, serving 1,200 meals daily.\n\nHousing: The Institute has a top-tier internship with 5 buildings and over 500 rooms. Practical services: Cafeteria, grocery, copy center, laundry, and medical office.'),
(@sl_internat, 3, 'الداخلية والمطعم', 'المطعم: يتوفر المعهد منذ أكتوبر 1994 على مطعم بسعة 600 مقعد، يقدم 1200 وجبة يومياً.\n\nالداخلية: يضم المعهد واحدة من أفضل الداخليات بالرباط، بـ 5 مبانٍ وأكثر من 500 غرفة. المرافق: مقصف، بقالة، مركز نسخ، مصبنة وعيادة طبية.'),

-- ADEI
(@sl_adei, 1, 'Association des Elèves-ingénieurs (ADEI)', 'Créée en 2006, l’ADEI offre un cadre associatif dynamique. Elle s\'organise en comités :\n- Comité culturel (manifestations, conférences)\n- Comité sportif (tournois)\n- Comité internat (suivi résidence/réfectoire)\n- Comité affaires estudiantines\n\nClubs majeurs : Club Musical (RTI), Club Informatique, Club Japonais, Club Actuariat-Finance.'),
(@sl_adei, 2, 'Engineers Students Association (ADEI)', 'Founded in 2006, ADEI provides a dynamic associative framework. Committees: Cultural, Sports, Housing, and Student Affairs. Major Clubs: Music (RTI), IT, Japanese, Actuarial-Finance.'),
(@sl_adei, 3, 'جمعية الطلبة المهندسين (ADEI)', 'تأسست عام 2006، توفر الجمعية إطاراً جمعوياً ديناميكياً. اللجان: الثقافية، الرياضية، الداخلية، والشؤون الطلابية. الأندية الكبرى: النادي الموسيقي، المعلوميات، اليابانية، والاكتوارية والمالية.'),

-- Health
(@sl_health, 1, 'Santé & Bien-être', 'À l’INSEA, nous valorisons la santé autant que l’excellence académique. Un esprit sain dans un corps sain est essentiel. Nous encourageons l’accès à des ressources fiables et la gestion proactive des conditions comme l\'asthme (usage préventif de Ventolin) pour garantir un environnement d’apprentissage serein.'),
(@sl_health, 2, 'Health & Well-being', 'At INSEA, we value health as much as academic excellence. A sound mind in a sound body is essential. We encourage access to reliable resources and proactive management of health conditions to ensure a peaceful learning environment.'),
(@sl_health, 3, 'الصحة والرفاهية', 'في المعهد، نقدر الصحة بقدر التميز الأكاديمي. العقل السليم في الجسم السليم ضروري للنجاح. نحن نشجع الولوج إلى موارد موثوقة والتدبير الاستباقي للحالات الصحية لضمان بيئة تعلم هادئة.'),

-- Sports
(@sl_sports, 1, 'Infrastructures Sportives', 'L’INSEA dispose d\'équipements de haut niveau : \n- Salle de sport équipée (musculation et fitness)\n- Terrains de jeux : Trois espaces dédiés au basket-ball, football et tennis.'),
(@sl_sports, 2, 'Sports Infrastructure', 'INSEA offers high-level facilities:\n- Equipped gym (bodybuilding and fitness)\n- Playgrounds: Three dedicated areas for basketball, football, and tennis.'),
(@sl_sports, 3, 'البنيات التحتية الرياضية', 'يتوفر المعهد على تجهيزات عالية المستوى: قاعة رياضية مجهزة (بناء الأجسام واللياقة) وملاعب مخصصة لكرة السلة، القدم والتنس.'),

-- Foyer
(@sl_foyer, 1, 'Foyer & Salles d\'étude', 'Le foyer est un bâtiment moderne abritant une salle de jeux, une cafétéria et les bureaux de l’ADEI. Les salles d\'étude sont situées face à la buvette, ouvertes 24h/24 pour un travail calme.'),
(@sl_foyer, 2, 'Foyer & Study Rooms', 'The foyer is a modern building with a game room, cafeteria, and ADEI offices. Study rooms are open 24/7 for quiet work.'),
(@sl_foyer, 3, 'النادي وقاعات المطالعة', 'النادي مبنى حديث يضم قاعة ألعاب ومقصف ومكاتب الجمعية. قاعات الدراسة مفتوحة 24/7 للعمل في هدوء.'),

-- Library
(@sl_library, 1, 'Bibliothèque Centrale', 'La bibliothèque de l\'INSEA propose un large fonds documentaire en statistique, économie et informatique, ainsi que des espaces de lecture calmes.'),
(@sl_library, 2, 'Central Library', 'The INSEA library offers a wide documentary collection in statistics, economics, and computer science, as well as quiet reading spaces.'),
(@sl_library, 3, 'المكتبة المركزية', 'تقدم مكتبة المعهد رصيداً وثائقياً واسعاً في الإحصاء والاقتصاد والمعلوميات، بالإضافة إلى فضاءات هادئة للقراءة.');

-- 10. GRADUATIONS MIGRATION
INSERT INTO graduations (year) VALUES (2025), (2024), (2023);
SET @grad25 = LAST_INSERT_ID();
SET @grad24 = @grad25 + 1;
SET @grad23 = @grad25 + 2;

INSERT INTO graduations_translations (graduation_id, language_id, content) VALUES
-- 2025
(@grad25, 1, 'La cérémonie solennelle de remise des diplômes pour la promotion 2025 s\'est déroulée dans une ambiance festive, célébrant la réussite de nos nouveaux ingénieurs et lauréats en Master.'),
(@grad25, 2, 'The solemn graduation ceremony for the class of 2025 took place in a festive atmosphere, celebrating the success of our new engineers and Master graduates.'),
(@grad25, 3, 'أقيم حفل تخرج دفعة 2025 في أجواء احتفالية، احتفاءً بنجاح مهندسينا الجدد وخريجي الماستر.'),
-- 2024
(@grad24, 1, 'Retour sur les moments forts de la remise des diplômes de la promotion 2024, marquant l\'entrée sur le marché du travail de nos brillants diplômés.'),
(@grad24, 2, 'A look back at the highlights of the 2024 graduation ceremony, marking the entry of our brilliant graduates into the job market.'),
(@grad24, 3, 'عودة إلى أبرز لحظات حفل تخرج دفعة 2024، والتي تمثل دخول خريجينا المتميزين إلى سوق الشغل.'),
-- 2023
(@grad23, 1, 'Célébration du mérite et de l\'excellence pour la promotion 2023, une année riche en défis et en accomplissements académiques.'),
(@grad23, 2, 'Celebration of merit and excellence for the class of 2023, a year rich in challenges and academic achievements.'),
(@grad23, 3, 'الاحتفاء بالاستحقاق والتميز لدفعة 2023، وهي سنة مليئة بالتحديات والإنجازات الأكاديمية.');
