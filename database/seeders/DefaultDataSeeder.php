<?php

namespace Database\Seeders;

use App\Models\BehavioralQuestion;
use App\Models\DemographicRestriction;
use App\Models\InterestAndPassionQuestion;
use App\Models\SkillQuestion;
use App\Models\SkillRestriction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Default Admin',
            'email' => 'admin@admin',
            'password' => bcrypt('admin123'),
            'church_code' => 'DEFAULT01',
            'church_name' => 'Church 1',
        ]);

        $this->createDemographicRestrictions($user->id);
        $this->createSkillRestrictions($user->id);
        $this->createSkillQuestions($user->id);
        $this->createInterestAndPassionQuestions($user->id);
        $this->createBehavioralQuestions($user->id);
    }

    private function createDemographicRestrictions(int $userId): void
    {
        $data = [
            [1, 0, 12, 99, 0, 1, 2],
            [2, 0, 12, 99, 0, 1, 2],
            [3, 0, 12, 99, 0, 1, 2],
            [4, 0, 15, 99, 0, 1, 3],
            [5, 0, 20, 99, 0, 1, 3],
            [6, 0, 15, 99, 0, 1, 3],
            [7, 0, 12, 25, 0, 1, 3],
            [8, 0, 20, 30, 0, 1, 3],
            [9, 1, 21, 99, 0, 1, 3],
            [10, 2, 21, 99, 0, 1, 3],
            [11, 0, 21, 99, 2, 1, 3],
            [12, 0, 1, 99, 0, 1, 3],
            [13, 0, 25, 99, 0, 1, 3],
            [14, 0, 25, 99, 0, 1, 3],
            [15, 0, 25, 99, 0, 1, 3],
            [16, 0, 25, 99, 0, 1, 3],
            [17, 0, 1, 99, 0, 1, 3],
            [18, 0, 25, 99, 0, 1, 3],
            [19, 0, 1, 99, 0, 1, 3],
            [20, 0, 21, 99, 0, 1, 3],
            [21, 0, 22, 99, 0, 1, 3],
            [22, 0, 22, 99, 0, 1, 3],
            [23, 0, 21, 99, 0, 1, 3],
            [24, 0, 21, 99, 0, 1, 3],
            [25, 0, 21, 99, 0, 1, 3],
            [26, 0, 21, 99, 0, 1, 3],
            [27, 0, 21, 99, 0, 1, 3],
            [28, 0, 60, 99, 0, 1, 3],
            [29, 0, 21, 99, 1, 1, 3],
        ];

        $records = [];
        foreach ($data as [$mid, $gender, $ageMin, $ageMax, $marital, $baptized, $faith]) {
            $records[] = [
                'user_id' => $userId,
                'ministry_id' => $mid,
                'gender' => $gender,
                'age_min' => $ageMin,
                'age_max' => $ageMax,
                'marital_status' => $marital,
                'baptized' => $baptized,
                'time_in_faith' => $faith,
            ];
        }
        DemographicRestriction::insert($records);
    }

    private function createSkillRestrictions(int $userId): void
    {
        $data = [
            [1, 1, 1, 0, 0, 0, 0, 0, 1],
            [2, 1, 1, 0, 0, 0, 0, 0, 1],
            [3, 1, 1, 0, 0, 0, 0, 0, 1],
            [4, 0, 0, 1, 0, 1, 0, 1, 1],
            [5, 0, 0, 1, 0, 1, 0, 1, 1],
            [6, 0, 0, 1, 0, 1, 0, 1, 1],
            [7, 0, 0, 0, 0, 0, 0, 0, 1],
            [8, 0, 0, 0, 0, 0, 0, 0, 1],
            [9, 0, 0, 0, 0, 0, 0, 1, 1],
            [10, 0, 0, 0, 0, 0, 0, 1, 1],
            [11, 0, 0, 0, 0, 0, 0, 1, 1],
            [12, 0, 0, 0, 0, 0, 0, 0, 1],
            [13, 0, 0, 1, 0, 0, 1, 0, 1],
            [14, 0, 0, 1, 0, 0, 1, 0, 1],
            [15, 0, 0, 0, 1, 0, 0, 0, 1],
            [16, 0, 0, 0, 1, 0, 0, 0, 1],
            [17, 0, 0, 1, 0, 1, 0, 1, 1],
            [18, 0, 0, 1, 0, 1, 0, 1, 1],
            [19, 0, 0, 1, 0, 1, 0, 1, 1],
            [20, 0, 0, 1, 0, 1, 0, 1, 1],
            [21, 0, 1, 1, 0, 0, 0, 0, 1],
            [22, 0, 1, 1, 0, 1, 0, 1, 1],
            [23, 0, 0, 0, 0, 0, 0, 1, 1],
            [24, 0, 0, 0, 0, 0, 0, 0, 1],
            [25, 1, 0, 0, 0, 1, 0, 0, 1],
            [26, 0, 0, 0, 0, 1, 0, 1, 1],
            [27, 0, 0, 0, 0, 0, 0, 0, 1],
            [28, 0, 0, 0, 0, 0, 0, 0, 1],
            [29, 0, 0, 0, 0, 0, 0, 0, 1],
        ];

        $records = [];
        foreach ($data as [$mid, $music, $tech, $writing, $technical, $speaking, $accounting, $mentoring, $bible]) {
            $records[] = [
                'user_id' => $userId,
                'ministry_id' => $mid,
                'music' => $music,
                'technology' => $tech,
                'writing' => $writing,
                'technical' => $technical,
                'speaking' => $speaking,
                'accounting' => $accounting,
                'mentoring' => $mentoring,
                'bible_knowledge' => $bible,
            ];
        }
        SkillRestriction::insert($records);
    }

    private function createSkillQuestions(int $userId): void
    {
        $questions = [
            // Music (skill_id = 1)
            [1, 1, 'I am confident in my ability to sing with proper pitch and rhythm.', 'May tiwala ako sa aking kakayahang umawit nang may tamang tono at ritmo.'],
            [1, 2, 'I am able to learn and perform dance routines when given instruction.', 'Kaya kong matutunan at isayaw ang mga rutinang itinuturo sa akin.'],
            [1, 3, 'I can play a musical instrument with basic skill and technique.', 'Kaya kong tumugtog ng isang instrumento gamit ang batayang kasanayan at tamang pamamaraan.'],
            [1, 4, 'I have experience expressing myself through singing, dancing, or playing music.', 'May karanasan ako sa pagpapahayag ng aking sarili sa pamamagitan ng pag-awit, pagsayaw, o pagtugtog ng musika.'],
            [1, 5, 'I have the ability to use my singing, dancing, or musical skills to contribute effectively in a ministry or group setting.', 'May kakayahan akong gamitin ang aking talento sa pag-awit, pagsayaw, o pagtugtog upang maging mabisa ang aking ambag sa isang ministeryo o grupo.'],
            // Technology (skill_id = 2)
            [2, 6, 'I can operate computers and use software applications to support church tasks.', 'Marunong akong gumamit ng kompyuter at mga software upang makatulong sa simbahan.'],
            [2, 7, 'I have skills in editing, design, or coding that can be used for ministry projects.', 'May kakayahan ako sa pag-eedit, pagdidisenyo, o coding para sa mga proyekto ng ministeryo.'],
            [2, 8, 'I can troubleshoot basic technical issues to help in church activities.', 'Kaya kong ayusin ang mga simpleng teknikal na problema sa mga gawain ng simbahan.'],
            [2, 9, 'I am able to learn and apply new digital tools that can benefit the church.', 'Handa akong matuto gumamit ng mga bagong digital tools na makabubuti sa simbahan.'],
            [2, 10, 'I can use my technological skills to serve and support church ministries effectively.', 'Magagamit ko ang aking tech skills upang makapagsilbi at makasuporta sa mga ministeryo ng simbahan.'],
            // Writing (skill_id = 3)
            [3, 11, 'I\'m able to put ideas into clear and engaging writing.', 'Nakakaya kong magsulat ng malinaw at nakakaengganyong ideya.'],
            [3, 12, 'Writing is something I do frequently in my personal or professional life.', 'Madalas kong ginagawa ang pagsusulat sa personal o propesyonal na buhay.'],
            [3, 13, 'I\'ve had past experiences creating articles, reports, or scripts.', 'May karanasan ako sa paggawa ng articles, reports, o scripts.'],
            [3, 14, 'I\'d like to further develop my writing abilities through practice or training.', 'Nais ko pang mapaunlad ang aking kakayahan sa pagsusulat sa pamamagitan ng pageensayo .'],
            [3, 15, 'I\'d like to contribute to church-related materials, articles, or programs through writing.', 'Gusto kong mag-ambag sa paggawa ng church materials, articles, o programs sa pamamagitan ng pagsusulat.'],
            // Technical (skill_id = 4)
            [4, 16, 'I\'m skilled in fixing, building, or handling technical tasks.', 'May kasanayan ako sa pagkukumpuni, paggawa, o paghawak ng mga teknikal na gawain.'],
            [4, 17, 'I often work on repair, maintenance, or hands-on technical projects.', 'Madalas akong gumagawa ng repair, maintenance, o iba pang hands-on na teknikal na proyekto.'],
            [4, 18, 'I have previous experience in electrical, plumbing, or mechanical work.', 'May karanasan na ako sa electrical, plumbing, o mechanical na trabaho.'],
            [4, 19, 'I\'d like to learn more about technical skills through guided training.', 'Gusto kong matuto pa ng mas maraming teknikal na skills sa pamamagitan ng guided training.'],
            [4, 20, 'I\'m willing to help the church with technical, electrical, or repair needs.', 'Handa akong tumulong sa simbahan pagdating sa teknikal, electrical, o repair na pangangailangan.'],
            // Speaking (skill_id = 5)
            [5, 21, 'I feel confident speaking in front of a group of people.', 'May kumpiyansa akong magsalita sa harap ng isang grupo ng tao.'],
            [5, 22, 'I regularly speak or present in front of audiences, big or small.', 'Madalas akong magsalita o magpresenta sa harap ng mga tagapakinig, maliit man o malaki.'],
            [5, 23, 'I\'ve had experience delivering talks, announcements, or presentations.', 'Nagkaroon na ako ng karanasan sa pagbibigay ng mga talumpati, anunsyo, o presentasyon.'],
            [5, 24, 'I can adjust my tone and pace when speaking to keep attention.', 'Kaya kong iakma ang aking tono at bilis ng pananalita upang mapanatili ang atensyon.'],
            [5, 25, 'I can prepare and organize a talk or presentation effectively.', 'Kaya kong maghanda at mag-ayos ng isang talumpati o presentasyon nang epektibo.'],
            // Accounting (skill_id = 6)
            [6, 26, 'I\'m good at keeping track of money, budgets, or financial records.', 'Magaling ako sa pagtatala ng pera, badyet, o mga rekord na pinansyal.'],
            [6, 27, 'I currently handle tasks that involve budgeting or expense tracking.', 'Kasalukuyan akong humahawak ng mga gawain na may kinalaman sa pagbabadget o pagsubaybay ng gastos.'],
            [6, 28, 'I\'ve had experience managing finances for a project or group.', 'Nagkaroon na ako ng karanasan sa pamamahala ng pananalapi para sa isang proyekto o grupo.'],
            [6, 29, 'I can prepare simple financial reports or summaries.', 'Kaya kong gumawa ng simpleng ulat o buod na pinansyal.'],
            [6, 30, 'I can ensure accuracy when recording and monitoring financial transactions.', 'Natitiyak kong tama ang pagtatala at pagsubaybay ng mga transaksyong pinansyal.'],
            // Mentoring (skill_id = 7)
            [7, 31, 'I have a natural ability to guide, encourage, or advise others.', 'May likas akong kakayahan na gumabay, magpalakas ng loob, o magbigay ng payo sa iba.'],
            [7, 32, 'I regularly help people through mentoring, counseling, or coaching.', 'Madalas akong tumutulong sa mga tao sa pamamagitan ng mentoring, counseling, o coaching.'],
            [7, 33, 'I have experience providing advice or support to individuals or groups.', 'May karanasan ako sa pagbibigay ng payo o suporta sa mga indibidwal o grupo.'],
            [7, 34, 'I can actively mentor or counsel people when needed.', 'Kaya kong aktibong mag-mentor o mag-counsel sa mga tao kapag kinakailangan.'],
            [7, 35, 'I am capable of giving guidance and support to others in an organized way.', 'May kakayahan akong magbigay ng gabay at suporta sa iba sa isang organisadong paraan.'],
            // Bible Knowledge (skill_id = 8)
            [8, 36, 'I feel confident in my understanding of Biblical stories and principles.', 'May tiwala ako sa aking pag-unawa sa mga kwento at prinsipyo sa Biblia.'],
            [8, 37, 'I often study and discuss the Bible.', 'Madalas akong mag-aral at makipag-usap tungkol sa Biblia.'],
            [8, 38, 'I\'ve had experience teaching or sharing lessons from Scripture.', 'Nagkaroon na ako ng karanasan sa pagtuturo o pagbabahagi ng aral mula sa Kasulatan.'],
            [8, 39, 'I\'d like to deepen my understanding of the Bible through study.', 'Nais kong palalimin ang aking pag-unawa sa Biblia sa pamamagitan ng pag-aaral.'],
            [8, 40, 'I\'d like to share my knowledge of Scripture in church activities.', 'Nais kong ibahagi ang aking kaalaman tungkol sa Kasulatan sa mga gawain ng simbahan.'],
        ];

        $records = [];
        foreach ($questions as [$skillId, $num, $en, $tl]) {
            $records[] = [
                'user_id' => $userId,
                'skill_id' => $skillId,
                'question_number' => $num,
                'question_en' => $en,
                'question_tl' => $tl,
            ];
        }
        SkillQuestion::insert($records);
    }

    private function createInterestAndPassionQuestions(int $userId): void
    {
        $questions = [
            // Core Ministry (ministry_category_id = 1)
            [1, 1, 'I enjoy helping others learn and grow a relationship with God.', 'Nasasiyahan akong tumulong sa iba na matuto at lumago ang kanilang relasyon sa Diyos.'],
            [1, 2, 'I like participating in or helping lead worship, prayer, or Bible study.', 'Gusto kong makibahagi o tumulong mamuno sa pagsamba, panalangin, o pag-aaral ng Biblia.'],
            [1, 3, 'I\'m interested in regularly engaging with specific age groups (children, youth, young adults, men/women).', 'Interesado akong regular na makisalamuha sa partikular na mga grupo ayon sa edad (mga bata, kabataan, young adults, kalalakihan/kababaihan).'],
            [1, 4, 'I like sharing biblical ideas and supporting others in their spiritual journey.', 'Gusto kong magbahagi ng mga ideya mula sa Biblia at sumuporta sa iba sa kanilang espirituwal na paglalakbay.'],
            [1, 5, 'I\'d like to be part of ministries that focus on spiritual growth and discipleship.', 'Gusto kong maging bahagi ng mga ministeryo na nakatuon sa espirituwal na paglago at discipleship.'],
            // Support Ministry (ministry_category_id = 2)
            [2, 6, 'I\'m interested in working behind the scenes to help the church run smoothly.', 'Interesado akong magtrabaho sa likod ng mga gawain upang maging maayos ang takbo ng simbahan.'],
            [2, 7, 'I enjoy organizing events, managing tasks, or helping with logistics.', 'Nasasayahan akong mag-organisa ng mga event, mag-manage ng mga gawain, o tumulong sa logistics.'],
            [2, 8, 'I like paying attention to details and making sure things run efficiently.', 'Gusto kong bigyang pansin ang mga detalye at matiyak na maayos ang takbo ng mga bagay.'],
            [2, 9, 'I enjoy helping create a welcoming and organized church environment.', 'Nasasiyahan akong tumulong sa paglikha ng isang magiliw at organisadong kapaligiran sa simbahan.'],
            [2, 10, 'I\'d like to be involved in roles such as ushering, administration, or facility care.', 'Gusto kong maging kasali sa mga tungkulin gaya ng ushering, administration, o pag-aalaga ng pasilidad.'],
            // Outreach Ministry (ministry_category_id = 3)
            [3, 11, 'I am motivated to assist individuals beyond the church community.', 'May hangarin akong tumulong sa mga tao sa labas ng komunidad ng simbahan.'],
            [3, 12, 'Sharing my faith and helping others experience God\'s love is important to me.', 'Mahalaga sa akin ang pagbabahagi ng aking pananampalataya at pagtulong sa iba na maranasan ang pag-ibig ng Diyos.'],
            [3, 13, 'I feel a personal calling toward community outreach and service.', 'Nararamdaman ko ang personal na pagtawag tungo sa community outreach at paglilingkod.'],
            [3, 14, 'I am interested about going where people are hurting and bringing hope.', 'Interesado akong pumunta sa mga lugar kung saan may mga taong nasasaktan at magdala ng pag-asa.'],
            [3, 15, 'I am interested in participating in ministries focused on evangelism and community impact.', 'Interesado akong sumali sa mga ministeryo na nakatuon sa evangelism at community impact.'],
            // Creative & Media Ministry (ministry_category_id = 4)
            [4, 16, 'I am passionate about using my creative talents and interests for God\'s glory.', 'Ipinapakita ko ang aking pagmamahal sa paggamit ng aking malikhaing talento at interes para sa kaluwalhatian ng Diyos.'],
            [4, 17, 'I enjoy working with visuals, media, technology, or the arts to communicate messages.', 'Nasasiyahan akong magtrabaho gamit ang biswal, media, teknolohiya, o sining upang maipahayag ang mga mensahe.'],
            [4, 18, 'I find fulfillment and energy in creating content, managing social media, or coordinating live events.', 'Natagpuan ko ang kasiyahan at sigla sa paggawa ng nilalaman, pamamahala ng social media, o pag-aayos ng mga live na gawain.'],
            [4, 19, 'I would like to help improve the church\'s communication and engagement through creative means.', 'Nais kong makatulong sa pagpapabuti ng komunikasyon at pakikipag-ugnayan ng simbahan sa pamamagitan ng malikhaing paraan.'],
            [4, 20, 'I am interested in using my skills in drama, dance, video, graphic design, or tech to contribute to ministry.', 'Interesado akong gamitin ang aking kakayahan sa dula, sayaw, video, graphic design, o teknolohiya upang makatulong sa ministeryo.'],
            // Care & Healing Ministry (ministry_category_id = 5)
            [5, 21, 'I feel sincere and have a desire to help those who are struggling or in need.', 'Magaan sa loob ko at may hangarin akong tumulong sa mga taong nahihirapan o nangangailangan.'],
            [5, 22, 'I am a good listener and often find people sharing their struggles with me.', 'Magaling akong makinig at madalas akong nasasabihan ng mga tao tungkol sa kanilang pinagdadaanan.'],
            [5, 23, 'I feel strongly called to help others heal emotionally, spiritually, or physically.', 'Malakas ang pakiramdam kong tinatawag akong tumulong sa paggaling ng iba—emosyonal, espiritwal, o pisikal.'],
            [5, 24, 'I am willing to give my time and understanding to support those in need.', 'Handa akong maglaan ng oras at pag-unawa para sumuporta sa nangangailangan.'],
            [5, 25, 'I am open and eager to serve in counseling, healing prayer, or support ministries.', 'Bukas ako at sabik na maglingkod sa pamamagitan ng counseling, panalangin para sa kagalingan, o mga support ministries.'],
            // Special Interest Ministry (ministry_category_id = 6)
            [6, 26, 'I am interested in ministering to specific groups such as seniors, singles, or working professionals.', 'Interesado akong maglingkod sa mga partikular na grupo gaya ng mga nakatatanda, single, o mga propesyonal.'],
            [6, 27, 'I enjoy connecting with others who share common interests or hobbies (e.g., sports, writing).', 'Nasasayahan akong makipag-ugnayan sa iba na may parehong interes o hilig (hal. sports, pagsusulat).'],
            [6, 28, 'I believe my personal experiences and skills can positively contribute to others\' growth.', 'Naniniwala ako na ang aking mga personal na karanasan at kakayahan ay makatutulong nang positibo sa paglago ng iba.'],
            [6, 29, 'I would enjoy leading or participating in ministries that align with my current life season or stage.', 'Magiging masaya ako na mamuno o makibahagi sa mga ministeryo na tumutugma sa aking kasalukuyang yugto ng buhay.'],
            [6, 30, 'I feel drawn to serve in creative or unconventional ministry areas that embrace unique interests.', 'Nararamdaman kong tinatawag ako na maglingkod sa malikhaing o kakaibang ministeryo na tumatanggap ng natatanging interes.'],
        ];

        $records = [];
        foreach ($questions as [$categoryId, $num, $en, $tl]) {
            $records[] = [
                'user_id' => $userId,
                'ministry_category_id' => $categoryId,
                'question_number' => $num,
                'question_en' => $en,
                'question_tl' => $tl,
            ];
        }
        InterestAndPassionQuestion::insert($records);
    }

    private function createBehavioralQuestions(int $userId): void
    {
        $ministryQuestions = [
            1 => [ // Worship (Singing)
                'I think I am good at singing and can carry a tune confidently.',
                'Sa tingin ko ay magaling ako sa pagkanta at kaya kong kumanta nang may kumpiyansa.',
                'I can sing with proper pitch, tone, and breathing control.',
                'Ako ay kasalukuyang nagsasanay ng pagkanta o gumagamit ng aking tinig nang regular sa paaralan, mga event, o sa personal na oras.',
                'I have performed or sung in front of an audience before.',
                'Ako ay nakapagtanghal o kumanta na sa harap ng madla dati.',
                'I am willing to learn proper vocal techniques to improve my singing.',
                'Handa akong matuto ng tamang teknik sa boses upang mapabuti ang aking pagkanta.',
                'I am willing to use my singing talent to serve in the church.',
                'Handa akong gamitin ang aking talento sa pagkanta upang maglingkod sa simbahan.',
            ],
            2 => [ // Worship (Dancing)
                'I think I am good at dancing and can learn choreography quickly.',
                'Sa tingin ko ay magaling ako sa pagsayaw at mabilis akong matuto ng choreography.',
                'I currently dance or practice movement regularly in school, events, or personal time.',
                'Ako ay kasalukuyang sumasayaw o nagsasanay ng galaw nang regular sa paaralan, mga event, o sa personal na oras.',
                'I have performed or joined a dance group or presentation before.',
                'Ako ay nakapagtanghal o nakasali na sa isang dance group o presentasyon dati.',
                'I am willing to learn new dance styles or techniques to improve my skills.',
                'Handa akong matuto ng mga bagong estilo o teknik sa pagsayaw upang mapabuti ang aking kakayahan.',
                'I am willing to use my dancing talent to serve in the church.',
                'Handa akong gamitin ang aking talento sa pagsayaw upang maglingkod sa simbahan.',
            ],
            3 => [ // Worship (Instrument)
                'I think I am good at playing at least one musical instrument.',
                'Sa tingin ko ay magaling ako tumugtog ng kahit isang instrumentong pangmusika.',
                'I currently practice or play an instrument regularly.',
                'Ako ay kasalukuyang nagsasanay o tumutugtog ng instrumento nang regular.',
                'I have performed or played an instrument in front of an audience before.',
                'Ako ay nakatugtog na o nakapagbigay-tanghal gamit ang isang instrumento sa harap ng madla dati.',
                'I am willing to learn new instruments or improve my existing skills.',
                'Handa akong matuto ng bagong instrumento o paghusayin ang kasalukuyan kong kakayahan.',
                'I am willing to use my instrumental talent to serve in the church.',
                'Handa akong gamitin ang aking talento sa pagtugtog ng instrumento upang maglingkod sa simbahan.',
            ],
            4 => [ // Prayer
                'A peaceful, prayer-focused environment would be fulfilling for me.',
                'Isang mapayapa at panalanging nakatuon na kapaligiran ang magiging kasiya-siya para sa akin.',
                'I am willing to spend regular time interceding for people, the church, and world needs.',
                'Handa akong maglaan ng regular na oras sa pananalangin para sa mga tao, simbahan, at pangangailangan ng mundo.',
                'I have the focus, persistence, and spiritual sensitivity to serve in prayer ministry.',
                'May konsentrasyon, tiyaga, at espiritwal na sensibilidad ako upang maglingkod sa prayer ministry.',
                'I believe I would serve well in a ministry centered on prayer.',
                'Naniniwala akong magiging maayos ang aking paglilingkod sa isang ministeryo na nakatuon sa panalangin.',
                'I find purpose in dedicating time to pray for the needs of others.',
                'Nakakakita ako ng layunin sa paglalaan ng oras upang ipanalangin ang mga pangangailangan ng iba',
            ],
            5 => [ // Preaching
                'I would enjoy spending time studying, preparing, and discussing God\'s Word.',
                'Mag-eenjoy ako sa pag-aaral, paghahanda, at pagtalakay ng Salita ng Diyos.',
                'I want to share biblical truths through preaching or teaching.',
                'Nais kong ibahagi ang mga katotohanang biblikal sa pamamagitan ng pangangaral o pagtuturo.',
                'I have the speaking ability, clarity, and understanding needed to communicate Scripture effectively.',
                'May kakayahan akong magsalita, malinaw na magpaliwanag, at maunawaan ang Biblia upang maiparating ito ng epektibo.',
                'I feel a strong responsibility to explain Scripture in a way that leads to life application.',
                'Ramdam ko ang tawag na ipaliwanag ang Salita ng Diyos sa paraang madaling isabuhay ng bawat isa',
                'I take notes from sermons and reflect on how I might explain those lessons to others.',
                'Naglalaan ako ng oras na magtala mula sa mga sermon at pag-isipan kung paano ko maipapaliwanag ang mga iyon sa iba.',
            ],
            6 => [ // Discipleship
                'I enjoy being in settings where people are guided and mentored in their faith.',
                'Masaya ako sa mga lugar kung saan ginagabayan at minementor ang mga tao sa pananampalataya.',
                'I am motivated to invest in others\' spiritual growth over time.',
                'Interesado akong tulungan ang iba na lumago sa kanilang espiritwal na buhay.',
                'I feel I have the patience, consistency, and listening skills that discipleship requires.',
                'May pasensya, palagian sa pagtulong, at kakayahang makinig ako para sa discipleship.',
                'I believe walking alongside others in faith is a good fit for me.',
                'Sa tingin ko bagay sa akin ang kasama ang iba sa kanilang pananampalataya.',
                'I am willing to help others apply biblical principles in their daily life decisions.',
                'Handa akong tumulong sa iba na maisabuhay ang mga prinsipyo ng Biblia sa kanilang araw-araw na mga desisyon.',
            ],
            7 => [ // Youth
                'I would feel energized in a setting filled with teens and youth activities.',
                'Masigla akong makaramdam sa lugar na puno ng aktibidad para sa teens at youth.',
                'I feel I have the patience, energy, and creativity to engage well with teens and youth.',
                'May pasensya, enerhiya, at kakayahan akong makipag-ugnayan nang maayos sa teens at youth.',
                'I believe I can contribute effectively to a ministry focused on youth growth and faith.',
                'Naniniwala akong makakatulong ako sa ministeryo na nakatuon sa paglago at pananampalataya ng youth.',
                'I think youth ministry fits my strengths and personality.',
                'Sa tingin ko bagay sa akin ang youth ministry at tugma sa aking kakayahan.',
                'I take initiative to help and participate whenever there are youth ministry activities.',
                'Nagpapakita ako ng kusa na tumulong at makibahagi kapag may mga aktibidad ang youth ministry.',
            ],
            8 => [ // Young Adults
                'I enjoy being in environments where young adults share life experiences and challenges.',
                'Masaya ako sa mga lugar kung saan nagbabahagi ang young adults ng kanilang karanasan at hamon sa buhay.',
                'I want to support people in their early career or college years in making faith-based decisions.',
                'Gusto kong tulungan ang mga tao sa kanilang early career o college years sa paggawa ng desisyon base sa pananampalataya.',
                'I have the empathy, understanding, and guidance skills needed for this ministry.',
                'May empatiya, pag-unawa, at kakayahan akong magbigay-gabay para sa ministeryong ito.',
                'I believe I could connect well with and serve in a young adults ministry.',
                'Naniniwala akong makakaugnay ako nang maayos at makakapaglingkod sa young adults ministry.',
                'I am open to building meaningful friendships and accountability with fellow young adults in the ministry.',
                'Bukas ako sa pagbuo ng makabuluhang pagkakaibigan at pananagutan kasama ng kapwa young adults sa ministry.',
            ],
            9 => [ // Men's
                'I would enjoy building strong, faith-based relationships with other men.',
                'Masaya ako na bumuo ng matibay at pananampalatayang relasyon sa ibang lalaki.',
                'I want to help men grow in integrity, leadership, and spiritual strength.',
                'Gusto kong tulungan ang mga lalaki na lumago sa integridad, pamumuno, at espiritwal na lakas.',
                'I have the openness, mentorship skills, and accountability mindset needed here.',
                'May openness, kakayahan sa mentorship, at pananagutan ako na kailangan dito.',
                'I believe the men\'s ministry is an area where I could have a strong impact.',
                'Naniniwala akong makakagawa ako ng malaking epekto sa men\'s ministry.',
                'I am interested in participating in group activities that strengthen men\'s faith and character.',
                'Interesado akong sumali sa mga group activities na nagpapalakas sa pananampalataya at karakter ng mga lalaki.',
            ],
            10 => [ // Women's
                'I would enjoy a setting where women gather for support, faith, and encouragement.',
                'Masaya ako sa lugar kung saan nagtitipon ang mga babae para sa suporta, pananampalataya, at pampalakas ng loob.',
                'I want to help women grow spiritually, emotionally, and in community.',
                'Gusto kong tulungan ang mga babae na lumago sa espiritwal, emosyonal, at komunidad.',
                'I have the compassion, leadership, and listening skills suited for women\'s ministry.',
                'May malasakit, kakayahan sa pamumuno, at pakikinig ako na bagay sa women\'s ministry.',
                'I believe I could thrive in a role that uplifts and supports women.',
                'Naniniwala akong magiging maayos ang aking paglilingkod sa isang tungkulin na nag-uplift at sumusuporta sa mga babae.',
                'I am interested in participating in group activities that strengthen women\'s faith and character.',
                'Interesado akong sumali sa mga group activities na nagpapalakas sa pananampalataya at karakter ng mga babae.',
            ],
            11 => [ // Family Or Couples
                'I would enjoy working in an environment that focuses on strengthening families and marriages.',
                'Masaya ako na magtrabaho sa lugar na nakatuon sa pagpapalakas ng pamilya at kasal.',
                'I want to support couples and families through events, teaching, or guidance.',
                'Gusto kong tulungan ang mga mag-asawa at pamilya sa pamamagitan ng events, pagtuturo, o gabay.',
                'I have the wisdom, patience, and relationship skills needed here.',
                'May karunungan, pasensya, at kakayahan sa relasyon ako na kailangan dito.',
                'I believe I could contribute positively to a ministry focused on families and couples.',
                'Naniniwala akong makakatulong ako sa ministeryo na nakatuon sa pamilya at mag-asawa.',
                'I enjoy participating in events or programs that help families build stronger bonds.',
                'Nasasaya ako sa pagsali sa mga events o programa na tumutulong sa pamilya na magkaroon ng mas matibay na ugnayan.',
            ],
            12 => [ // Ushering
                'I would enjoy being in a welcoming, people-focused environment.',
                'Masaya ako sa lugar na nakatuon sa pagtanggap at serbisyo sa tao.',
                'I want to help create a warm and friendly atmosphere during services or events.',
                'Gusto kong tulungan lumikha ng mainit at magiliw na kapaligiran sa mga serbisyo o events.',
                'I have the friendliness, alertness, and helpfulness needed for ushering.',
                'May kaibigan, alerto, at handa akong tumulong na kakayahan ako para sa ushering.',
                'I believe I would do well in a ministry that serves as the first point of contact for guests.',
                'Naniniwala akong magaling akong maglingkod sa ministeryo na unang nakakatagpo sa mga bisita.',
                'I enjoy coordinating and assisting with logistics to make church events run smoothly.',
                'Nasasaya ako sa pagtulong at pag-aayos ng mga gawain para maging maayos ang church events.',
            ],
            13 => [ // Administrative
                'I would enjoy working in an environment that involves organization and planning.',
                'I would enjoy working in an environment that involves organization and planning.',
                'I want to help manage schedules, records, or communications for the ministry.',
                'I want to help manage schedules, records, or communications for the ministry.',
                'I have the attention to detail, time management, and organization skills needed.',
                'I have the attention to detail, time management, and organization skills needed.',
                'I believe I could be effective in an administrative role.',
                'I believe I could be effective in an administrative role.',
                'I have considered serving in an administrative capacity before.',
                'Nasasaya ako sa pag-coordinate at pag-organize ng mga gawain para mas maging epektibo ang ministry.',
            ],
            14 => [ // Finance
                'I enjoy helping manage and organize church finances and resources responsibly.',
                'Nasasaya ako sa pagtulong sa maayos at responsableng pamamahala ng finances at resources ng simbahan.',
                'I am confident in handling budgeting, records, and financial tasks accurately.',
                'May kumpiyansa ako sa paghawak ng budgeting, records, at financial tasks nang tama.',
                'I like tracking and monitoring expenses to ensure proper stewardship.',
                'Gusto kong subaybayan at i-monitor ang gastusin upang masiguro ang tamang stewardship.',
                'I have the attention to detail, reliability, and accountability needed for this ministry.',
                'May kakayahan ako sa detalye, pagiging maaasahan, at pananagutan na kailangan sa ministeryong ito.',
                'I am interested in using my financial and stewardship skills to support church activities.',
                'Interesado akong gamitin ang aking financial at stewardship skills para suportahan ang mga gawain ng simbahan.',
            ],
            15 => [ // Marshal
                'I would enjoy being in an environment focused on safety and order.',
                'Masaya ako sa lugar na nakatuon sa kaligtasan at kaayusan.',
                'I am interested in helping maintain safety and well-being during church events.',
                'Interesado akong tumulong sa pagpapanatili ng kaligtasan at kapakanan sa mga church events.',
                'I feel I have the alertness, calmness, and decisiveness needed for Marshal work.',
                'May alertness, kalmadong disposisyon, at pagiging matatag ako na kailangan sa Marshal work.',
                'I believe I could fulfill the role of keeping events safe and orderly.',
                'Naniniwala akong magaling akong gampanan ang tungkulin na panatilihing ligtas at maayos ang mga events.',
                'I enjoy helping create a safe and secure environment for everyone.',
                'Nasasaya ako sa pagtulong na magkaroon ng ligtas at maayos na kapaligiran para sa lahat.',
            ],
            16 => [ // Facilities Maintenance
                'I would enjoy being in a hands-on, task-focused work environment.',
                'Masaya ako sa lugar na hands-on at nakatuon sa mga gawain.',
                'I am interested in helping maintain, clean, and prepare church spaces.',
                'Interesado akong tumulong sa pagpapanatili, paglilinis, at paghahanda ng mga pasilidad ng simbahan.',
                'I believe I have the practical skills, energy, and attention to detail needed for this role.',
                'Naniniwala akong may praktikal na kakayahan, enerhiya, at pansin sa detalye ako na kailangan sa tungkuling ito.',
                'I feel capable of contributing effectively to keeping church facilities in good condition.',
                'Pakiramdam ko kaya kong makapag-ambag nang epektibo sa pagpapanatili ng maayos na kondisyon ng simbahan.',
                'I enjoy helping maintain and organize church facilities.',
                'Nasasaya ako sa pagtulong sa pagpapanatili at pag-aayos ng mga pasilidad ng simbahan.',
            ],
            17 => [ // Evangelism
                'I would enjoy being in an environment that actively connects with new people.',
                'Masaya ako sa lugar na aktibong nakikipag-ugnayan sa mga bagong tao.',
                'I am interested in sharing the message of Jesus through everyday conversations.',
                'Interesado akong ibahagi ang mensahe ni Jesus sa pamamagitan ng pang-araw-araw na pag-uusap.',
                'I feel I have the confidence, friendliness, and openness needed for evangelism.',
                'Pakiramdam ko may kumpiyansa, pagiging palakaibigan, at bukas na disposisyon ako na kailangan sa evangelism.',
                'I believe I could be effective in reaching out to others with the Gospel.',
                'Naniniwala akong magiging epektibo ako sa pag-abot sa iba gamit ang Ebanghelyo.',
                'I enjoy sharing God\'s message with others and helping them grow in faith.',
                'Nasasaya ako sa pagbabahagi ng mensahe ng Diyos sa iba at pagtulong sa kanilang paglago sa pananampalataya.',
            ],
            18 => [ // Missions
                'I would enjoy serving in different cultural or unfamiliar environments.',
                'Masaya ako sa paglilingkod sa iba\'t ibang kultura o hindi pamilyar na kapaligiran.',
                'I want to participate in outreach, whether locally or globally.',
                'Interesado akong lumahok sa outreach, lokal man o global.',
                'I have the adaptability, teamwork, and openness needed for missions work.',
                'May kakayahan ako sa adaptability, teamwork, at pagiging bukas na disposisyon na kailangan sa missions work.',
                'I believe I could contribute meaningfully in a missions setting.',
                'Naniniwala akong makakapag-ambag ako ng may kahulugan sa missions setting.',
                'I am interested to take part in long-term or short-term mission projects.',
                'Interesado akong makilahok sa long-term o short-term na mission projects.',
            ],
            19 => [ // Community Service
                'I would enjoy being in an environment focused on serving the community.',
                'Masaya ako sa lugar na nakatuon sa paglilingkod sa komunidad.',
                'I want to help meet practical needs for people in my area.',
                'Interesado akong tumulong sa pagtugon sa praktikal na pangangailangan ng mga tao sa aking lugar.',
                'I have the willingness, compassion, and work ethic needed for community service.',
                'May kagustuhan, malasakit, at sipag ako na kailangan sa community service.',
                'I believe I could make a difference through service-based ministry.',
                'Naniniwala akong makakagawa ako ng pagbabago sa pamamagitan ng service-based ministry.',
                'I enjoy actively helping improve the lives of people in my community.',
                'Nasasaya ako sa aktibong pagtulong na mapabuti ang buhay ng mga tao sa aking komunidad.',
            ],
            20 => [ // Visitation
                'I like spending time with and encouraging people who are sick, alone, or going through a hard time.',
                'Nasasaya ako sa pakikipag-ugnayan at pagbibigay ng suporta sa mga may sakit, nag-iisa, o dumadaan sa mahirap na panahon.',
                'I want to spend time offering comfort and presence to others.',
                'Gusto kong maglaan ng oras sa pagbibigay ng aliw at presensya sa iba.',
                'I have the empathy, patience, and gentleness needed for visitation work.',
                'May empathy, pasensya, at kabaitan ako na kailangan sa visitation work.',
                'I believe I could offer meaningful support through this ministry.',
                'Naniniwala akong makakapagbigay ako ng makahulugang suporta sa pamamagitan ng ministeryong ito.',
                'I feel fulfilled when I can provide care and companionship to those who are struggling.',
                'Pakiramdam ko ay nasisiyahan ako kapag nakakapagbigay ako ng pag-aalaga at kasama sa mga taong nahihirapan.',
            ],
            21 => [ // Production Tech
                'I would enjoy being in a setting that uses technology, screens, and equipment to enhance services.',
                'Masaya ako sa lugar na gumagamit ng teknolohiya, screens, at kagamitan upang pagandahin ang mga serbisyo.',
                'I want to help operate sound, video, lighting, or livestream tools.',
                'Gusto kong tumulong sa pagpapatakbo ng sound, video, lighting, o livestream tools.',
                'I have the technical skill, focus, and adaptability needed for media work.',
                'May teknikal na kakayahan, focus, at adaptability ako na kailangan sa media work.',
                'I believe I could serve effectively in a tech or media ministry.',
                'Naniniwala akong makakapaglingkod ako nang epektibo sa tech o media ministry.',
                'I enjoy using my technical skills to support and enhance church services.',
                'Nasasaya ako sa paggamit ng aking teknikal na kakayahan upang suportahan at pagandahin ang mga serbisyo ng simbahan.',
            ],
            22 => [ // Creative & Social Media
                'I enjoy capturing moments, events, and stories through photography or videography.',
                'Nasasaya ako sa pagkuha ng mga sandali, kaganapan, at kwento sa pamamagitan ng photography o videography.',
                'I want to help document and share church activities through visual content like photos and videos.',
                'Gusto kong tumulong sa pagdodokumento at pagbabahagi ng mga aktibidad ng simbahan gamit ang visual content tulad ng photos at videos.',
                'I have the creativity and technical skills needed for photography or video production.',
                'May creativity at teknikal na kakayahan ako na kailangan para sa photography o video production.',
                'I think I could contribute meaningfully to an online outreach ministry.',
                'Naniniwala akong makakapag-ambag ako nang may kahulugan sa online outreach ministry.',
                'I enjoy using social media to connect with and inspire the church community.',
                'Nasasaya ako sa paggamit ng social media upang makipag-ugnayan at magbigay inspirasyon sa komunidad ng simbahan.',
            ],
            23 => [ // Counseling
                'I would feel fulfilled serving in a ministry that supports people through life\'s emotional challenges.',
                'Ako ay makakaramdam ng kasiyahan kung ako ay makapaglilingkod sa isang ministeryo na tumutulong sa mga tao sa kanilang emosyonal na hamon sa buhay.',
                'I am interested to help individuals or families find healing and clarity through conversation and prayer.',
                'Ako ay interesado na makatulong sa mga indibidwal o pamilya upang makahanap ng kagalingan at kaliwanagan sa pamamagitan ng pag-uusap at panalangin.',
                'I believe I have the empathy, wisdom, and discretion needed for counseling.',
                'Naniniwala ako na taglay ko ang empatiya, karunungan, at pagiging maingat na kailangan sa pagkonsulta o pakikinig.',
                'I believe I could serve well in a role that offers guidance and emotional support.',
                'Naniniwala ako na makapaglilingkod ako nang maayos sa isang tungkulin na nagbibigay ng gabay at emosyonal na suporta.',
                'I feel called to use my gifts in listening and compassion to support a counseling or care ministry.',
                'Nararamdaman kong ako ay tinawag upang gamitin ang aking kakayahan sa pakikinig at malasakit upang makatulong sa isang ministeryo ng pagkalinga o pagkonsulta.',
            ],
            24 => [ // Healing & Deliverance
                'I would like to be part of a ministry that focuses on prayer for healing and freedom.',
                'Nais kong maging bahagi ng isang ministeryo na nakatuon sa panalangin para sa kagalingan at kalayaan.',
                'I would like to pray for people\'s physical, emotional, or spiritual restoration.',
                'Nais kong ipanalangin ang pisikal, emosyonal, o espirituwal na paggaling ng mga tao.',
                'I feel I have the sensitivity, discernment, and perseverance for this type of ministry.',
                'Nararamdaman ko na taglay ko ang pagiging sensitibo, pagkilala sa kalooban ng Diyos, at pagtitiyaga na kailangan para sa ganitong uri ng ministeryo.',
                'I believe I could serve effectively in healing and deliverance work.',
                'Naniniwala ako na makapaglilingkod ako nang mabisa sa gawain ng pagpapagaling at pagpapalaya.',
                'I am willing to dedicate my time and myself to be an instrument of prayer for the healing and freedom of others.',
                'Handa akong italaga ang oras at sarili ko upang maging kasangkapan sa panalangin para sa kagalingan at pagpapalaya ng iba.',
            ],
            25 => [ // Funeral
                'I would feel honored to walk alongside people during seasons of grief and loss.',
                'Ikinalulugod kong makasama ang mga tao sa panahon ng dalamhati at pagkawala.',
                'I am comfortable being present with others in sorrow without needing to give quick answers.',
                'Komportable akong makasama ang iba sa kanilang kalungkutan kahit hindi agad magbigay ng kasagutan.',
                'I have the compassion, gentleness, and stability needed for this ministry.',
                'Taglay ko ang habag, kahinahunan, at katatagan na kailangan para sa ministeryong ito.',
                'I believe I could serve well in comforting those who are grieving.',
                'Naniniwala akong makapaglilingkod ako nang maayos sa pagpapalakas ng loob ng mga nagdadalamhati.',
                'I am willing to offer my time and presence to support individuals and families in their journey through grief.',
                'Handa kong ialay ang aking oras at presensya upang masuportahan ang mga indibidwal at pamilya sa kanilang paglalakbay sa pagdadalamhati.',
            ],
            26 => [ // Addiction Recovery
                'I would be committed to helping people find freedom from harmful habits or addictions.',
                'Ikokommite ko ang sarili ko sa pagtulong sa mga tao na makalaya mula sa mapanirang gawi o adiksyon.',
                'I want to support others with grace and patience through the recovery process.',
                'Nais kong suportahan ang iba nang may biyaya at pagtitiyaga sa proseso ng kanilang paggaling.',
                'I have the understanding, encouragement, and nonjudgmental attitude needed for this work.',
                'Taglay ko ang pang-unawa, paghimok, at hindi mapanghusgang pag-uugali na kailangan para sa gawaing ito.',
                'I believe I could contribute meaningfully to an addiction recovery ministry.',
                'Naniniwala ako na makapag-aambag ako nang makabuluhan sa isang ministeryo ng pagbangon mula sa adiksyon.',
                'I am willing to walk alongside individuals consistently as they pursue healing and lasting recovery.',
                'Handa akong samahan ang mga indibidwal nang tuloy-tuloy habang sila ay nagsusumikap para sa kagalingan at pangmatagalang pagbabago.',
            ],
            27 => [ // Special Needs
                'I would find joy in creating safe and welcoming spaces for people with special needs.',
                'Matatagpuan ko ang kagalakan sa paglikha ng ligtas at magiliw na lugar para sa mga taong may espesyal na pangangailangan.',
                'I want to help individuals of all abilities feel valued and included.',
                'Nais kong tulungan ang mga indibidwal na may iba\'t ibang kakayahan na maramdaman na sila ay mahalaga at kabilang.',
                'I have the patience, adaptability, and compassion needed for this ministry.',
                'Taglay ko ang pasensya, kakayahang umangkop, at malasakit na kailangan para sa ministeryong ito.',
                'I believe I could serve well in supporting people with special needs and their families.',
                'Naniniwala ako na makapaglilingkod ako nang maayos sa pagsuporta sa mga taong may espesyal na pangangailangan at kanilang pamilya.',
                'I am willing to dedicate my time and energy to build meaningful connections with individuals with special needs.',
                'Handa kong italaga ang aking oras at lakas upang makabuo ng makahulugang ugnayan sa mga indibidwal na may espesyal na pangangailangan.',
            ],
            28 => [ // Seniors
                'I would feel fulfilled serving in a ministry that supports and honors older adults.',
                'Makakaramdam ako ng kasiyahan sa paglilingkod sa isang ministeryo na sumusuporta at nagbibigay-pugay sa mga nakatatanda.',
                'I am interested in spending time listening to and learning from seniors\' life experiences.',
                'Interesado akong maglaan ng oras sa pakikinig at pagkatuto mula sa mga karanasan sa buhay ng mga nakatatanda.',
                'I believe I have the patience, kindness, and respect needed to serve elderly individuals.',
                'Naniniwala ako na taglay ko ang pasensya, kabaitan, at paggalang na kailangan upang maglingkod sa mga nakatatandang indibidwal.',
                'I believe I could thrive in a role that offers companionship and practical care for seniors.',
                'Naniniwala akong uunlad ako sa isang tungkulin na nag-aalok ng pakikipagkaibigan at praktikal na pag-aalaga para sa mga nakatatanda.',
                'I would find purpose in helping seniors share their wisdom and feel a continued sense of belonging.',
                'Makakakita ako ng layunin sa pagtulong sa mga nakatatanda na maibahagi ang kanilang karunungan at maramdaman na sila ay patuloy na kabilang.',
            ],
            29 => [ // Single Adults
                'I would enjoy being part of a community that connects and encourages single adults.',
                'Magiging kasiya-siya para sa akin ang maging bahagi ng isang komunidad na nag-uugnay at nagbibigay-lakas sa mga walang asawa.',
                'I am interested in participating in gatherings that support friendship and faith growth among singles.',
                'Interesado akong makilahok sa mga pagtitipon na sumusuporta sa pagkakaibigan at paglago sa pananampalataya ng mga single.',
                'I feel I have the empathy, openness, and encouragement needed for this ministry.',
                'Nararamdaman kong taglay ko ang empatiya, pagiging bukas, at kakayahang magpalakas ng loob na kailangan para sa ministeryong ito.',
                'I believe I could contribute to helping single adults feel supported and connected.',
                'Naniniwala akong makapag-aambag ako sa pagtulong sa mga single adults na maramdaman ang suporta at koneksyon.',
                'I value the opportunity to affirm that single adults are an important and vital part of the church community.',
                'Pinahahalagahan ko ang pagkakataong ipakita na ang mga single adults ay mahalaga at may mahalagang bahagi sa komunidad ng simbahan.',
            ],
        ];

        $records = [];
        foreach ($ministryQuestions as $ministryId => $q) {
            for ($i = 0; $i < 5; $i++) {
                $records[] = [
                    'user_id' => $userId,
                    'ministry_id' => $ministryId,
                    'question_number' => $i + 1,
                    'question_en' => $q[$i * 2],
                    'question_tl' => $q[$i * 2 + 1],
                ];
            }
        }
        BehavioralQuestion::insert($records);
    }
}
