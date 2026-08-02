export const initialCategories = [
  { id: 1, name_uz: 'Muhandislik va Konstruksiyalar', name_ru: 'Инженерия и Конструкции', name_en: 'Engineering & Structure', description: 'Structural analysis, design optimizations, seismic resistance, load calculations.' },
  { id: 2, name_uz: 'Yashil Qurilish va Materiallar', name_ru: 'Зеленое Строительство и Материалы', name_en: 'Green Building & Materials', description: 'Eco-friendly concrete, thermal insulation, sustainable composites, energy efficiency.' },
  { id: 3, name_uz: 'Infratuzilma va Transport', name_ru: 'Инфраструктура и Транспорт', name_en: 'Infrastructure & Transport', description: 'Roadways, bridges, smart pavement technologies, public transport integration.' },
  { id: 4, name_uz: 'Geotexnika Muhandisligi', name_ru: 'Геотехническая Инженерия', name_en: 'Geotechnical Engineering', description: 'Soil mechanics, deep foundations, slope stability, underground structures.' },
  { id: 5, name_uz: 'Aqlli Qurilish va IoT', name_ru: 'Умное Строительство и IoT', name_en: 'Smart Construction & IoT', description: 'BIM modeling, sensor integration, drones in surveying, site management automation.' },
];

export const initialProblems = [
  {
    id: 1,
    company_id: 2,
    company_name: 'Apex Construction LLC',
    category_id: 1,
    category_name: 'Muhandislik va Konstruksiyalar',
    title: 'Yuqori seysmik hududlar uchun yengil va mustahkam beton konstruksiya ishlanmasi',
    description: 'Toshkent va Seysmik faol zonalarda koʻp qavatli binolar vaznini 25% ga kamaytiruvchi, lekin mustahkamligini saqlaydigan innovatsion nanobeton aralashmasi retsepturasini ishlab chiqish lozim.',
    requirements: '1. M400 dan kam boʻlmagan mustahkamlik kuchi.\n2. Zichligi 1800 kg/m3 dan oshmasligi.\n3. Seysmik tebranish sinovlarining kompyuter modeli.',
    expected_result: 'Laboratoriya sinov bayonnomasi va aralashma texnologik xaritasi.',
    budget: 15000.00,
    deadline: 1789000000,
    status: 10, // 10 = Open, 20 = In Review, 30 = Solved
    views_count: 142,
    created_at: 1784700593,
  },
  {
    id: 2,
    company_id: 4,
    company_name: 'Qurilish MCHJ',
    category_id: 2,
    category_name: 'Yashil Qurilish va Materiallar',
    title: 'Mahalliy xomashyolardan energiya tejamkor issiqlik izolyatsiyasi panellari',
    description: 'Oʻzbekistondagi mahalliy bazalt va qishloq xoʻjaligi chiqindilaridan tayyorlanadigan, yonmaydigan va arzon issiqlik saqlash panellarini ishlab chiqarish texnologiyasi.',
    requirements: '1. Yonish klassi A1 (yonmaydigan).\n2. Issiqlik oʻtkazuvchanlik katsiyenti λ <= 0.035 W/m·K.\n3. Ishlab chiqarish tannarxi 1 kv.m uchun 8$ dan oshmasligi.',
    expected_result: 'Tayyor namuna va patentlangan kimyoviy tarkib kodi.',
    budget: 8000.00,
    deadline: 1788500000,
    status: 10,
    views_count: 98,
    created_at: 1784784041,
  },
  {
    id: 3,
    company_id: 2,
    company_name: 'Apex Construction LLC',
    category_id: 5,
    category_name: 'Aqlli Qurilish va IoT',
    title: 'BIM va Sunʼiy Intellekt yordamida qurilish obyektlari xavfsizligini monitoring qilish',
    description: 'Qurilish maydonidagi kameralar va sensorlardan kelayotgan videolarni tahlil qilib, ishchilarning kaska va xavfsizlik kamari taqqanligini avtomatik aniqlovchi AI tizimi.',
    requirements: '1. Real vaqt rejimida Video stream ishlovi.\n2. Aniqlik darajasi 95% dan yuqori.\n3. Telegram bot orqali tezkor ogohlantirish.',
    expected_result: 'Web va Mobile platformada ishlovchi dasturiy taʼminot MVP.',
    budget: 12000.00,
    deadline: 1789900000,
    status: 20,
    views_count: 215,
    created_at: 1784750000,
  }
];

export const initialProposals = [
  {
    id: 1,
    problem_id: 1,
    scientist_id: 3,
    scientist_name: 'Prof. Alisher Usmanov',
    scientist_degree: 'Doctor of Science (DSc)',
    institution: 'Tashkent State Technical University',
    title: 'Bazalt mikrotola bilan boyitilgan ultra yengil nanobeton yechimi',
    description: 'Laboratoriyamizda 3 yil davomida sinovdan oʻtgan bazalt mikrotolali beton retseptini taqdim etamiz. Zichligi 1750 kg/m3, mustahkamligi M450 ga yetadi.',
    solution_details: 'Kimyoviy qoʻshimchalar va mahalliy bazalt tolalarining 1.2% nisbatdagi aralashmasi orqali erishiladi.',
    budget_offer: 13500.00,
    time_offer: '45 kun',
    status: 10, // 10 = Draft/Submitted, 30 = Accepted, 40 = Rejected
    created_at: 1784784672,
  }
];

export const initialUsers = [
  { id: 1, username: 'admin', email: 'admin@antigravity.uz', role: 'administrator' },
  { id: 2, username: 'company1', email: 'company@apex.uz', role: 'company' },
  { id: 3, username: 'scientist1', email: 'scientist@tstu.uz', role: 'scientist' }
];
