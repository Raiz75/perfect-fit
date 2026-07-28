# Ministry Info Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the `/ministries` page showing all 29 ministries grouped by 6 categories with descriptions and requirements tables.

**Architecture:** Database stores descriptions in a new `description` column on the `ministries` table. Controller loads categories + ministries + default restrictions (user ID 1). View renders independent carousels per category with vanilla JS.

**Tech Stack:** Laravel 12, Blade, MySQL, vanilla JS carousels

## Global Constraints

- Ministry IDs 1-29 are frozen — seeder must reference by these IDs
- Restrictions come from user ID 1 (the template user)
- All content is read-only — no forms, no AJAX, no Livewire on this page
- Category color scheme (same as landing page): Core=#8c52ff, Support=#2dce89, Outreach=#fb6340, Creative=#f5365c, Care=#11cdef, Special Interest=#2dce89

---

### Task 1: Add `description` column + seed descriptions

**Files:**
- Modify: `database/migrations/0001_01_01_000004_create_ministries_table.php`
- Create: `database/seeders/MinistryDescriptionSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Add description TEXT column to the migration**

Edit `database/migrations/0001_01_01_000004_create_ministries_table.php` — add `$table->text('description')->nullable();` after the `name` column:

```diff
$table->id();
$table->string('name');
+ $table->text('description')->nullable();
$table->foreignId('ministry_category_id')->nullable()->constrained()->nullOnDelete();
```

- [ ] **Step 2: Create MinistryDescriptionSeeder**

Create `database/seeders/MinistryDescriptionSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Ministry;
use Illuminate\Database\Seeder;

class MinistryDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = [
            1 => 'The Worship (Singing) Ministry exists to lead the congregation into heartfelt praise and worship through music. Members use their vocal abilities to create an atmosphere where people can encounter God\'s presence. This ministry emphasizes spiritual preparation, teamwork, and excellence in delivery, understanding that worship is not just performance but an offering to God. Singers also support special events, church gatherings, and outreach services, making worship accessible to people of all ages. Training often includes vocal exercises, learning new songs, and practicing harmonies. Their goal is to help the church focus on God and encourage a lifestyle of continuous worship.',
            2 => 'The Worship (Dancing) Ministry expresses worship through movement and choreography. Members of this ministry use dance to visually convey the message of God\'s love, grace, and power. Their purpose is to inspire others to worship in freedom, using creative expressions that go beyond words. Dancers prepare for services and special events, performing pieces that align with sermons or themes. This ministry requires discipline, practice, and sensitivity to the Spirit, as dance is not for entertainment but as an act of worship. It also allows individuals to use their physical talents to glorify God and lead others closer to Him.',
            3 => 'The Worship (Instrument) Ministry provides the instrumental foundation for church worship. Musicians play instruments such as guitars, keyboards, drums, or other instruments to support congregational singing and create a dynamic worship atmosphere. This ministry requires both musical skill and spiritual commitment, as members must play with excellence while remaining humble servants. Practices and rehearsals are essential to ensure unity and flow in worship. Beyond Sunday services, they may also accompany prayer gatherings, youth services, and outreach events. Their primary purpose is not to showcase talent but to help the congregation experience God\'s presence through the beauty of instrumental music.',
            4 => 'The Prayer Ministry serves as the church\'s spiritual backbone, dedicating time to intercede for individuals, families, the congregation, and the world. Members gather regularly to pray for healing, guidance, revival, and breakthroughs. They also support the church during services, praying for pastors, leaders, and worship teams as they minister. This ministry emphasizes spiritual discipline, faith, and compassion for others. Members may also offer prayer counseling to those in need, encouraging people with scripture and intercession. The Prayer Ministry plays a vital role in spiritual warfare, covering the church in prayer and ensuring that every activity is rooted in God\'s will.',
            5 => 'The Preaching Ministry focuses on teaching and proclaiming God\'s Word with clarity and conviction. Preachers are called to study scripture deeply, prepare messages faithfully, and deliver sermons that inspire transformation. Their role is not only to inform but also to challenge, encourage, and guide believers in their spiritual journey. This ministry requires dedication to continuous learning, prayer, and dependence on the Holy Spirit. Preachers often take part in worship services, bible studies, and outreach events. They must also model Christlike living, as their influence extends beyond the pulpit. Their ultimate purpose is to spread the gospel and build strong disciples.',
            6 => 'The Discipleship Ministry is responsible for helping believers grow in their faith and spiritual maturity. Members of this ministry guide new Christians, teaching them biblical principles, prayer, and practical ways to live as followers of Christ. Through small groups, bible studies, and mentoring, they create opportunities for deeper learning and accountability. Discipleship leaders are patient, caring, and committed to walking alongside others in their spiritual journey. They help believers apply scripture to daily life and encourage them to develop a personal relationship with God. The ultimate goal is to equip disciples who, in turn, can disciple others and multiply faith.',
            7 => 'The Youth Ministry engages and nurtures the younger generation, guiding teenagers to live Christ-centered lives. Through bible studies, fellowship, worship nights, and community activities, this ministry provides a safe space for young people to grow spiritually and socially. Leaders act as mentors and role models, encouraging youth to develop their talents, serve in church, and make wise decisions in life. The ministry emphasizes building strong relationships, addressing real-life challenges, and empowering youth to stand firm in their faith. By involving them in church activities, it prepares the next generation of leaders and strengthens their commitment to God\'s calling.',
            8 => 'The Young Adults Ministry serves individuals transitioning from youth to adulthood, often those in college or starting their careers. This ministry provides support for spiritual, emotional, and professional growth during a crucial stage of life. Through bible studies, fellowships, and service opportunities, young adults find a community where they can share experiences and grow in faith. Leaders encourage them to pursue godly values while facing modern challenges such as relationships, work, and independence. The ministry aims to build confident, faith-driven adults who can influence their workplaces, communities, and families for Christ while staying connected to the church family.',
            9 => 'The Men\'s Ministry is dedicated to equipping men to fulfill their roles as leaders in their homes, churches, and communities. This ministry encourages men to grow spiritually, emotionally, and relationally through prayer meetings, bible studies, and mentorship. Activities often focus on strengthening family relationships, developing integrity, and providing support in facing challenges unique to men. Fellowship events also allow men to build strong bonds, encourage accountability, and find encouragement. The Men\'s Ministry seeks to raise godly men who are responsible, compassionate, and faithful, serving as role models for younger generations and standing firm as pillars of strength in the church.',
            10 => 'The Women\'s Ministry provides a supportive and nurturing community for women of all ages. It focuses on encouraging women to grow in their faith, strengthen their families, and use their gifts to serve others. Activities may include bible studies, prayer groups, workshops, and fellowship events tailored to the needs of women. This ministry often emphasizes spiritual growth, emotional support, and mentorship, helping women navigate different life seasons. It also empowers women to discover their unique callings and contribute actively to the church\'s mission. The Women\'s Ministry celebrates sisterhood and unity, helping women flourish in their walk with Christ.',
            11 => 'The Family or Couples Ministry is dedicated to strengthening marriages and families through biblical guidance and fellowship. This ministry provides support for couples at different stages of life, from newlyweds to parents with children. Programs often include marriage enrichment seminars, counseling, prayer sessions, and family-centered activities. The goal is to nurture strong, Christ-centered relationships that can withstand life\'s challenges. Leaders encourage open communication, forgiveness, and teamwork within families, emphasizing God\'s design for marriage. By fostering healthy households, this ministry not only strengthens individual families but also builds a stronger church community rooted in love and unity.',
            12 => 'The Ushering Ministry plays a vital role in creating a welcoming and orderly atmosphere during church services and events. Ushers are often the first people members and visitors meet, so they represent the warmth and hospitality of the church. Their responsibilities include greeting attendees, helping people find seats, collecting offerings, distributing materials, and providing directions when needed. Ushers also ensure that services run smoothly by maintaining order and assisting during emergencies. This ministry requires patience, attentiveness, and a heart for service. Ultimately, the Ushering Ministry reflects God\'s love through hospitality, helping everyone feel valued and comfortable in worship.',
            13 => 'The Administrative Ministry ensures that the church operates efficiently by managing records, schedules, and communication. Members of this ministry handle clerical work, organize files, oversee correspondence, and assist with planning church programs or events. They may also prepare reports, manage supplies, and coordinate with leaders across different ministries. This ministry requires organizational skills, attention to detail, and a sense of responsibility, as their work supports the smooth functioning of church operations. Although often behind the scenes, their contribution is essential to ensuring that pastoral staff and ministry leaders can focus on their spiritual duties while administrative tasks are managed effectively.',
            14 => 'The Finance Ministry manages the church\'s financial resources with transparency, accountability, and stewardship. Members are responsible for counting offerings, recording contributions, preparing budgets, and ensuring funds are used according to the church\'s mission. They may also provide financial counseling to members and organize fundraising events. Integrity is crucial in this ministry, as financial decisions directly impact the church\'s ability to serve its community and support missions. Beyond bookkeeping, this ministry also teaches biblical principles of giving and stewardship, encouraging members to honor God with their resources. Their goal is to handle church finances faithfully and responsibly for God\'s glory.',
            15 => 'The Marshal Ministry is dedicated to maintaining safety, security, and order within church premises. Members oversee crowd management, especially during large gatherings, ensuring that people can worship without distractions. They may guide parking, assist with seating, and act quickly in emergencies. This ministry requires discipline, vigilance, and a calm presence, as marshals often serve as first responders in unexpected situations. They work closely with ushers and leaders to provide a safe worship environment. More than just security, the Marshal Ministry demonstrates care and responsibility, helping the church function peacefully while protecting both members and visitors during services and events.',
            16 => 'The Facilities Maintenance Ministry ensures that the church building and equipment are kept in excellent condition. Members handle repairs, cleaning, and upkeep of areas such as worship halls, classrooms, offices, and restrooms. They may also take charge of setting up equipment, maintaining electrical or sound systems, and ensuring everything runs smoothly during services. This ministry requires practical skills, dedication, and a willingness to serve behind the scenes. Though often unnoticed, their work directly impacts the comfort and safety of everyone who attends church. The Facilities Maintenance Ministry is a crucial part of stewardship, caring for the resources God has provided.',
            17 => 'The Evangelism Ministry is focused on spreading the gospel of Jesus Christ to those who have not yet accepted Him as Lord and Savior. Members actively share their faith through personal witnessing, community outreach, and organized evangelistic events. They may conduct Bible studies, distribute gospel tracts, and engage in conversations that lead people to Christ. Evangelists often work outside the church walls, reaching out to neighborhoods, workplaces, schools, and public spaces. This ministry requires courage, compassion, and a deep love for people. Its mission is to fulfill the Great Commission by bringing hope and salvation to the lost through the message of Jesus.',
            18 => 'The Missions Ministry extends the church\'s reach beyond local communities to other regions and even nations. Members support and participate in mission trips, partnering with local churches and organizations to spread the gospel and provide humanitarian aid. Their work often includes teaching, medical missions, and relief operations for those in need. This ministry requires cultural sensitivity, flexibility, and a servant\'s heart, as missionaries adapt to different environments and lifestyles. They also raise awareness within the congregation about global missions, encouraging prayer and financial support. Ultimately, they embody the heart of God for the nations and the unity of His church worldwide.',
            19 => 'The Community Service Ministry demonstrates God\'s love through practical acts of kindness and service. Members engage in projects that benefit local communities, such as feeding programs, clothing drives, clean-up campaigns, and educational support. They aim to meet physical, emotional, and spiritual needs by being the hands and feet of Jesus in everyday life. This ministry requires compassion, teamwork, and a willingness to serve people from all walks of life. Beyond providing aid, community service builds relationships, strengthens trust, and creates opportunities to share the gospel. Through their efforts, the church becomes a light to society, showing love in action.',
            20 => 'The Visitation Ministry reaches out personally to members and non-members who may need encouragement, prayer, or companionship. Members visit the sick, elderly, bereaved, and those unable to attend church services due to health or personal reasons. They also connect with new members to help them feel welcomed and integrated into the church family. This ministry requires empathy, patience, and the ability to listen with care. By being present in times of joy and sorrow, the Visitation Ministry reflects God\'s compassion and strengthens relationships within the church. It reminds people that they are not forgotten but are valued members of God\'s family.',
            21 => 'The Production Tech Ministry is responsible for managing the technical aspects of worship and church events. Members operate sound systems, lighting, video recording, live streaming, and projection equipment to ensure a smooth and engaging service experience. This ministry supports both on-site and online worshippers by creating a clear, distraction-free environment where God\'s Word can be effectively communicated. It requires technical skills, attention to detail, and the ability to troubleshoot under pressure. Beyond just handling equipment, production tech volunteers work as behind-the-scenes servants who make worship more accessible. Their efforts allow the gospel to reach not only those in the sanctuary but also people across the globe through digital platforms.',
            22 => 'The Creative & Social Media Ministry uses creativity and digital platforms to communicate the church\'s message and inspire others. Members design graphics, write content, capture photos and videos, and manage the church\'s presence on social media platforms. They create engaging posts, devotionals, and announcements that reach a wide audience and reflect the church\'s values. This ministry requires artistic talent, storytelling skills, and an understanding of digital trends. By using visual design and media strategically, they help spread the gospel beyond the church walls, connecting with people where they already spend much of their time. Ultimately, the ministry blends creativity with purpose, showing that art and technology can be powerful tools for advancing God\'s kingdom.',
            23 => 'The Counseling Ministry provides emotional, mental, and spiritual support to individuals and families within the church and community. Members offer guidance based on biblical principles, helping people navigate struggles such as stress, grief, relationships, or personal challenges. Counselors listen with compassion, provide encouragement, and pray for healing and clarity. This ministry requires empathy, wisdom, and confidentiality, as people often share deep and personal issues. Its goal is to bring restoration and hope through God\'s Word, pointing individuals toward a deeper relationship with Christ. Ultimately, the Counseling Ministry acts as a safe space for healing hearts and strengthening faith.',
            24 => 'The Healing & Deliverance Ministry is focused on praying for people who need spiritual, emotional, or physical healing. Members intercede for the sick, oppressed, and those struggling with burdens that hinder their walk with God. They minister with faith, using prayer, Scripture, and the power of the Holy Spirit to bring freedom and restoration. This ministry requires spiritual maturity, discernment, and compassion, as it deals with sensitive situations and deep struggles. It emphasizes that true healing comes from Christ, who is the ultimate healer. Through their prayers and service, members demonstrate God\'s love and power to transform lives.',
            25 => 'The Funeral Ministry serves grieving families by providing comfort, guidance, and practical support during one of life\'s most difficult moments. Members help with funeral arrangements, coordinate services, and ensure that families feel the compassion of the church community. They also offer prayers, scripture readings, and words of encouragement that point to the hope of eternal life in Christ. This ministry requires sensitivity, patience, and the ability to walk alongside others in grief. By serving in times of loss, the Funeral Ministry reminds people of God\'s presence and promises, providing strength and comfort when it is needed most.',
            26 => 'The Addiction Recovery Ministry supports individuals who are struggling with addictions such as drugs, alcohol, gambling, or other harmful habits. Members provide a safe and non-judgmental environment where people can find encouragement, accountability, and spiritual guidance. They may organize support groups, Bible studies, and mentoring relationships to help individuals overcome bondage and experience freedom in Christ. This ministry requires patience, understanding, and a strong foundation in God\'s Word, as recovery is often a long and challenging process. By walking with people step by step, the Addiction Recovery Ministry offers hope and reminds them that lasting change is possible through God\'s power.',
            27 => 'The Special Needs Ministry provides care, support, and inclusion for individuals with physical, mental, or developmental challenges. Members assist families by ensuring that every person, regardless of ability, feels welcomed and valued in the church. They may offer specialized programs, accessible facilities, and one-on-one support during worship services or events. This ministry requires patience, compassion, and creativity in adapting teaching methods and activities. It highlights the truth that every individual is uniquely created and loved by God. Through their service, the Special Needs Ministry helps break barriers, showing that the church is a place where everyone belongs and can experience God\'s love.',
            28 => 'The Seniors Ministry is dedicated to caring for and empowering older members of the church. It provides fellowship, encouragement, and opportunities for seniors to continue serving God and others. Activities may include Bible studies, prayer groups, social gatherings, and outreach projects designed to keep seniors spiritually and socially engaged. This ministry also offers support for health concerns, emotional needs, and life transitions such as retirement or loss. It requires patience, respect, and deep appreciation for the wisdom and experiences of older generations. By honoring and valuing seniors, the ministry reminds the church that every stage of life has purpose and dignity in God\'s kingdom.',
            29 => 'The Single Adults Ministry serves individuals who are unmarried, whether young professionals, those who have never married, or those who are divorced or widowed. It creates a supportive community where singles can grow spiritually, build meaningful friendships, and discover their unique calling. Members may participate in Bible studies, service projects, mentoring, and social activities that foster fellowship and personal growth. This ministry requires inclusivity, encouragement, and sensitivity to the diverse needs of single adults. It emphasizes that being single is not a limitation but an opportunity to focus fully on God and His plans. Ultimately, it empowers individuals to thrive in faith, purpose, and community.',
        ];

        foreach ($descriptions as $id => $description) {
            Ministry::where('id', $id)->update(['description' => $description]);
        }
    }
}
```

- [ ] **Step 3: Register the seeder in DatabaseSeeder**

Edit `database/seeders/DatabaseSeeder.php`:

```diff
$this->call([
    MinistryCategorySeeder::class,
    MinistrySeeder::class,
    SkillSeeder::class,
+   MinistryDescriptionSeeder::class,
    DefaultDataSeeder::class,
]);
```

Add the import at the top:
```diff
+ use Database\Seeders\MinistryDescriptionSeeder;
```

- [ ] **Step 4: Run migration + seeder to verify**

```bash
php artisan migrate:fresh --seed
```

Expected: No errors, 29 ministries now have descriptions.

---

### Task 2: Update FrontendController to pass data

**Files:**
- Modify: `app/Http/Controllers/FrontendController.php`

- [ ] **Step 1: Add imports and data logic**

Replace the `ministries()` method:

```php
<?php

namespace App\Http\Controllers;

use App\Models\DemographicRestriction;
use App\Models\MinistryCategory;
use App\Models\Skill;
use App\Models\SkillRestriction;

class FrontendController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function ministries()
    {
        $categories = MinistryCategory::with('ministries')->orderBy('id')->get();
        $demographicRestrictions = DemographicRestriction::where('user_id', 1)
            ->get()
            ->keyBy('ministry_id');
        $skillRestrictions = SkillRestriction::where('user_id', 1)
            ->get()
            ->keyBy('ministry_id');
        $skills = Skill::orderBy('id')->get();

        return view('ministries', compact(
            'categories',
            'demographicRestrictions',
            'skillRestrictions',
            'skills'
        ));
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }
}
```

---

### Task 3: Create the ministries Blade view

**Files:**
- Create: `resources/views/ministries.blade.php`

- [ ] **Step 1: Create the full view**

Create `resources/views/ministries.blade.php`:

```blade
@extends('_layouts.master')

@section('title', 'Ministries — PERFIT')

@push('head')
<style>
    :root {
        --cat-core: #8c52ff;
        --cat-support: #2dce89;
        --cat-outreach: #fb6340;
        --cat-creative: #f5365c;
        --cat-care: #11cdef;
        --cat-special: #2dce89;
    }

    .ministry-hero {
        padding-top: 120px;
        padding-bottom: 60px;
        background: linear-gradient(135deg, #faf8ff 0%, #f0e6ff 50%, #faf8ff 100%);
        position: relative;
        overflow: hidden;
    }

    .ministry-section {
        padding: 60px 0;
        scroll-margin-top: 80px;
    }

    .ministry-section:nth-child(even) {
        background: linear-gradient(180deg, #faf8ff 0%, #f8f4ff 100%);
    }

    .category-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
    }

    .category-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .carousel-container {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(140,82,255,0.06);
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    }

    .carousel-track {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .carousel-slide {
        flex: 0 0 100%;
        padding: 40px;
    }

    .carousel-slide h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 16px;
    }

    .carousel-slide .description {
        color: #4a3a6e;
        line-height: 1.8;
        margin-bottom: 24px;
        text-align: justify;
    }

    .requirements-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .requirements-table caption {
        font-weight: 700;
        color: #5a35b0;
        margin-bottom: 8px;
        text-align: left;
        font-size: 0.95rem;
    }

    .requirements-table th,
    .requirements-table td {
        border: 1px solid rgba(140,82,255,0.15);
        padding: 10px 12px;
        text-align: center;
    }

    .requirements-table th {
        background: rgba(140,82,255,0.06);
        color: #5a35b0;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .requirements-table td {
        color: #4a3a6e;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        border: 1px solid rgba(140,82,255,0.15);
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .carousel-btn:hover {
        background: #8c52ff;
        box-shadow: 0 8px 25px rgba(140,82,255,0.3);
        transform: translateY(-50%) scale(1.1);
        border-color: #8c52ff;
    }

    .carousel-btn:hover i {
        color: #fff !important;
    }

    .carousel-btn-prev { left: 12px; }
    .carousel-btn-next { right: 12px; }

    .carousel-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 16px 0;
    }

    .carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d0c4e8;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        padding: 0;
    }

    .carousel-dot.active {
        background: #8c52ff;
        transform: scale(1.3);
    }

    .ministry-section .section-fade {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ministry-section .section-fade.revealed {
        opacity: 1;
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .carousel-slide { padding: 24px 16px; }
        .carousel-slide h3 { font-size: 1.2rem; }
        .requirements-table { font-size: 0.75rem; }
        .requirements-table th,
        .requirements-table td { padding: 6px 4px; }
        .carousel-btn { width: 36px; height: 36px; }
    }
</style>
@endpush

@section('content')

<section class="ministry-hero text-center">
    <div class="container">
        <span class="d-inline-block px-3 py-1 rounded-pill fw-semibold text-uppercase mb-3" style="font-size: .75rem; letter-spacing: .1em; background: #ebdefb; color: #8c52ff;">
            Discover Your Calling
        </span>
        <h1 class="display-4 fw-bold mb-3">
            Explore <span style="color: #8c52ff;">Ministries</span>
        </h1>
        <p class="text-muted mx-auto" style="max-width: 560px; font-size: 1.1rem;">
            Find where your gifts and passion can make a difference in the church.
        </p>
    </div>
</section>

@php
$categoryColors = [
    1 => 'var(--cat-core)',
    2 => 'var(--cat-support)',
    3 => 'var(--cat-outreach)',
    4 => 'var(--cat-creative)',
    5 => 'var(--cat-care)',
    6 => 'var(--cat-special)',
];

$skillColumns = [
    'music', 'technology', 'writing', 'technical',
    'speaking', 'accounting', 'mentoring', 'bible_knowledge',
];
@endphp

@foreach($categories as $catIndex => $category)
<section id="cat-{{ $category->id }}" class="ministry-section">
    <div class="container">
        <div class="section-fade">
            <div class="category-header">
                <div class="category-dot" style="background: {{ $categoryColors[$category->id] ?? '#8c52ff' }};"></div>
                <h2 class="fw-bold mb-0" style="color: #1a1a2e;">{{ $category->name }}</h2>
            </div>

            @php $catId = 'carousel-' . $category->id; @endphp

            <div class="carousel-container" id="{{ $catId }}">
                <div class="carousel-track">
                    @foreach($category->ministries as $ministry)
                        @php
                            $demo = $demographicRestrictions->get($ministry->id);
                            $skillRest = $skillRestrictions->get($ministry->id);

                            $ageDisplay = 'any';
                            if ($demo) {
                                if ($demo->age_min && $demo->age_max) {
                                    $ageDisplay = $demo->age_min . '-' . $demo->age_max;
                                } elseif ($demo->age_min) {
                                    $ageDisplay = $demo->age_min . '+';
                                }
                            }

                            $activeSkills = [];
                            if ($skillRest) {
                                foreach ($skills as $skill) {
                                    $col = \Illuminate\Support\Str::snake($skill->name);
                                    if ($skillRest->$col ?? false) {
                                        $activeSkills[] = $skill->name;
                                    }
                                }
                            }
                        @endphp

                        <div class="carousel-slide">
                            <h3>{{ $ministry->name }}</h3>
                            @if($ministry->description)
                                <p class="description">{{ $ministry->description }}</p>
                            @endif

                            @if($demo)
                            <table class="requirements-table">
                                <caption>Requirements to Join</caption>
                                <thead>
                                    <tr>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                        <th>Baptized</th>
                                        <th>Time in Faith</th>
                                        <th>Skills</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $demo->gender ? ucfirst($demo->gender) : 'any' }}</td>
                                        <td>{{ $ageDisplay }}</td>
                                        <td>{{ $demo->marital_status ? ucfirst($demo->marital_status) : 'any' }}</td>
                                        <td>{{ $demo->baptized ? 'Yes' : 'No' }}</td>
                                        <td>{{ $demo->time_in_faith ?? 'any' }}</td>
                                        <td>{{ !empty($activeSkills) ? implode(', ', $activeSkills) : 'None specified' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($category->ministries->count() > 1)
                <button class="carousel-btn carousel-btn-prev" type="button" aria-label="Previous">
                    <i class="ti ti-chevron-left" style="font-size: 1.3rem; color: #8c52ff;"></i>
                </button>
                <button class="carousel-btn carousel-btn-next" type="button" aria-label="Next">
                    <i class="ti ti-chevron-right" style="font-size: 1.3rem; color: #8c52ff;"></i>
                </button>
                <div class="carousel-dots"></div>
                @endif
            </div>
        </div>
    </div>
</section>
@endforeach

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.carousel-container').forEach(function (container) {
        const track = container.querySelector('.carousel-track');
        const slides = container.querySelectorAll('.carousel-slide');
        const prevBtn = container.querySelector('.carousel-btn-prev');
        const nextBtn = container.querySelector('.carousel-btn-next');
        const dotsContainer = container.querySelector('.carousel-dots');

        if (slides.length <= 1) return;

        let currentIndex = 0;

        slides.forEach(function (_, i) {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
            dot.addEventListener('click', function () {
                goTo(i);
            });
            dotsContainer.appendChild(dot);
        });

        function goTo(index) {
            currentIndex = index;
            track.style.transform = 'translateX(-' + (index * 100) + '%)';
            dotsContainer.querySelectorAll('.carousel-dot').forEach(function (d, i) {
                d.classList.toggle('active', i === index);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                goTo((currentIndex - 1 + slides.length) % slides.length);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                goTo((currentIndex + 1) % slides.length);
            });
        }
    });

    const fadeEls = document.querySelectorAll('.section-fade');
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    fadeEls.forEach(function (el) { observer.observe(el); });
</script>
@endpush
```

---

### Task 4: Update topnav to link to /ministries route

**Files:**
- Modify: `resources/views/_partials/topnav.blade.php`

- [ ] **Step 1: Change Ministries nav link from anchor to route**

Replace `#ministries` with `{{ route('ministries') }}`:

```diff
<a class="nav-link px-3 py-2 rounded-pill nav-scroll" href="{{ route('ministries') }}" style="color: #1a1a2e; font-weight: 500; transition: all .2s;">Ministries</a>
```

---

## Self-Review Checklist

1. **Spec coverage:** All spec requirements covered — migration (description column), seeder (29 descriptions), controller data (categories, restrictions), view (6 category sections with carousels, requirements tables), styles (purple palette, glassmorphism, animations).
2. **Placeholder scan:** No TBD, TODO, or placeholder code in any step.
3. **Type consistency:** `demographicRestrictions` keyed by `ministry_id` (int), `skillRestrictions` keyed by `ministry_id` (int), `skills` as Eloquent collection, `skillColumns` array matches `SkillRestriction` fillable fields, `Str::snake()` mapping matches skill names to restriction columns.
4. **Scope check:** Focused on single page — no scope creep.
